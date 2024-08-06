<head>
    <title>อัพเดทข้อมูล</title>
    <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400&display=swap" rel="stylesheet">
    <style>
        *{
            font-family: 'Kanit', sans-serif;
        }
    </style>
</head>
<?php
    session_start();
    include '../server.php';
    if (!empty($_POST)) {
        $espId = $_POST['espId'];
        $temp = $_POST['temperature'];
        $hum = $_POST['humidity'];
        $line_token = "sTHVpTL5dxB6yuJbYVDqmzqVBKrW3e9tLFQnnBPmd0Q";
        date_default_timezone_set("Asia/Bangkok");
        $logData = date('Y-m-d H:i:s') . " - ";
        $currentDate = date('Y-m-d');

        // เริ่ม transaction
        $conn->begin_transaction();

        // ตรวจสอบและดึงข้อมูลจากฐานข้อมูลโดยใช้ espId
        $sql = "SELECT e.*, u.name FROM tbl_esp32_status e 
                JOIN tbl_user u ON e.user_id = u.id 
                WHERE e.esp_id = $espId";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $data = $result->fetch_assoc();
            $userId = $data['user_id'];
            $userName = $data['name'];
            $heat_index = round(calculateHeatIndex($temp, $hum), 2);
            $risk_status = '';

            // กำหนดสถานะความเสี่ยง
            if ($heat_index < 32) {
                $risk_status = "ปกติ";
            } elseif ($heat_index >= 32 && $heat_index < 38) {
                $risk_status = "ปานกลาง";
            } elseif ($heat_index >= 38 && $heat_index < 46) {
                $risk_status = "สุ่มเสี่ยง";
                $message = "\nชื่อผู้ใช้: $userName\nอุณหภูมิ: $temp °C\nความชื้น: $hum %\nความเสี่ยง: $risk_status";
                sendLineNotifyMessage($line_token, $message);
            } else {
                $risk_status = "อันตราย";
                $message = "\nชื่อผู้ใช้: $userName\nอุณหภูมิ: $temp °C\nความชื้น: $hum %\nความเสี่ยง: $risk_status";
                sendLineNotifyMessage($line_token, $message);
            }

            // อัปเดตข้อมูลใน tbl_esp32_status
            $update_sql = "UPDATE tbl_esp32_status SET 
                temp = '$temp',
                humidity = '$hum',
                heat_index = '$heat_index',
                status_name = '$risk_status',
                last_updated = NOW()
                WHERE esp_id = $espId";
            $result = $conn->query($update_sql);

            if ($result === TRUE) {
                //$logData .= "อัปเดตข้อมูลเรียบร้อยแล้ว\n";
            } else {
                $logData .= "เกิดข้อผิดพลาดในการอัปเดตข้อมูล: " . $conn->error . "\n";
                $conn->rollback();
                file_put_contents('log.txt', $logData, FILE_APPEND);
                exit;
            }

            // ตรวจสอบและอัปเดตค่าเฉลี่ยใน tbl_daily_avg
            $avg_sql = "SELECT * FROM tbl_daily_avg WHERE esp_id = $espId AND date = '$currentDate'";
            $avgResult = $conn->query($avg_sql);

            if ($avgResult === false) {
                $logData .= "Error selecting data: " . $conn->error . "\n";
                $conn->rollback();
                file_put_contents('log.txt', $logData, FILE_APPEND);
                exit;
            } else {
                if ($avgResult->num_rows > 0) {
                    // อัปเดตค่าเฉลี่ย
                    $avgData = $avgResult->fetch_assoc();
                    $newAvgTemp = ($avgData['temp'] + $temp) / 2;
                    $newAvgHumidity = ($avgData['humidity'] + $hum) / 2;
                    $newAvgHeatIndex = round(($avgData['heat_index'] + $heat_index) / 2, 2);

                    $update_avg_sql = "UPDATE tbl_daily_avg SET 
                        temp = '$newAvgTemp',
                        humidity = '$newAvgHumidity',
                        heat_index = '$newAvgHeatIndex'
                        WHERE esp_id = $espId AND date = '$currentDate'";
                    if ($conn->query($update_avg_sql) === false) {
                        $logData .= "Error updating data: " . $conn->error . "\n";
                        $conn->rollback();
                        file_put_contents('log.txt', $logData, FILE_APPEND);
                        exit;
                    } else {
                        //$logData .= "อัปเดตข้อมูลรายวันเรียบร้อยแล้ว\n";
                    }
                } else {
                    // เพิ่มข้อมูลใหม่
                    $insert_avg_sql = "INSERT INTO tbl_daily_avg (esp_id, temp, humidity, heat_index, date) VALUES (
                        '$espId',
                        '$temp',
                        '$hum',
                        '$heat_index',
                        '$currentDate'
                    )";
                    if ($conn->query($insert_avg_sql) === false) {
                        $logData .= "Error inserting data: " . $conn->error . "\n";
                        $conn->rollback();
                        file_put_contents('log.txt', $logData, FILE_APPEND);
                        exit;
                    } else {
                        $logData .= "เพิ่มข้อมูลรายวันเรียบร้อยแล้ว\n";
                    }
                }
            }

            // บันทึกข้อมูลลงในไฟล์ log.txt
            file_put_contents('log.txt', $logData, FILE_APPEND);
            // คอมมิต transaction
            $conn->commit();

        } else {
            $insertESP_sql = "INSERT INTO tbl_esp32_status (esp_id, temp, humidity, last_updated) VALUES (
                            '$espId',
                            '$temp',
                            '$hum',
                            NOW()
            )";
            $result_insert = $conn->query($insertESP_sql);
            $logData .= "เพิ่มข้อมูล ESPID ในฐานข้อมูล ID : $espId $conn->error\n";
            file_put_contents('log.txt', $logData, FILE_APPEND);
            // คอมมิต transaction
            $conn->commit();
        }
    }else{
        file_put_contents('log.txt', date('Y-m-d H:i:s') . " - " . "ไม่พบการส่งข้อมูลมา\n", FILE_APPEND);
    }

    function calculateHeatIndex($temperature, $humidity) {
        // Constants for heat index calculation
        $c1 = -42.379;
        $c2 = 2.04901523;
        $c3 = 10.14333127;
        $c4 = -0.22475541;
        $c5 = -6.83783e-3;
        $c6 = -5.481717e-2;
        $c7 = 1.22874e-3;
        $c8 = 8.5282e-4;
        $c9 = -1.99e-6;

        // Convert temperature from Celsius to Fahrenheit
        $temperatureFahrenheit = $temperature * (9 / 5) + 32;

        // Calculate heat index in Fahrenheit
        $heatIndexFahrenheit = $c1 + $c2 * $temperatureFahrenheit + $c3 * $humidity + $c4 * $temperatureFahrenheit * $humidity +
                                $c5 * $temperatureFahrenheit * $temperatureFahrenheit + $c6 * $humidity * $humidity +
                                $c7 * $temperatureFahrenheit * $temperatureFahrenheit * $humidity +
                                $c8 * $temperatureFahrenheit * $humidity * $humidity +
                                $c9 * $temperatureFahrenheit * $temperatureFahrenheit * $humidity * $humidity;

        // Convert heat index from Fahrenheit to Celsius
        $heatIndexCelsius = ($heatIndexFahrenheit - 32) * (5 / 9);

        return $heatIndexCelsius;
    }

    // ฟังก์ชันสำหรับส่งข้อความไปยัง Line Notify
    function sendLineNotifyMessage($accessToken, $message) {
        // URL ของ Line Notify API
        $url = 'https://notify-api.line.me/api/notify';
        
        // ข้อมูลที่จะส่ง
        $data = array('message' => $message);
        
        // ตัวเลือกของ HTTP header
        $options = array(
            'http' => array(
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n"
                            . "Authorization: Bearer $accessToken\r\n",
                'method'  => 'POST',
                'content' => http_build_query($data)
            )
        );
        
        // สร้าง context สำหรับ HTTP request
        $context  = stream_context_create($options);
        
        // ส่ง HTTP request ไปยัง Line Notify API
        $result = file_get_contents($url, false, $context);
        
        // ตรวจสอบการส่งข้อความ
        if ($result === FALSE) {
            // หากเกิดข้อผิดพลาด
            return "เกิดข้อผิดพลาดในการส่งข้อความไปยัง Line Notify: $result";
        } else {
            // หากสำเร็จ
            return "ส่งข้อความไปยัง Line Notify เรียบร้อยแล้ว";
        }
    }
?>