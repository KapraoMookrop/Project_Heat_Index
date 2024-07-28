<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="/Project_Heat_Index/css/style.css?v555">
    <link rel="icon" type="png" href="img/logo.png">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="container">
        <?php 
            include "server.php";
            $sql_daily = "SELECT temp, humidity, heat_index, date FROM tbl_daily_avg WHERE esp_id = '211264898497920'";
            $result_daily = $conn->query($sql_daily);

            $sql_current = "SELECT temp, humidity, heat_index FROM tbl_esp32_status WHERE esp_id = '211264898497920'";
            $result_current = $conn->query($sql_current);
            $row_current = $result_current->fetch_assoc();
            $temp = $row_current['temp'];
            $hum = $row_current['humidity'];
            $heat = $row_current['heat_index'];

            $data_daily = array();

            if ($result_daily->num_rows > 0) {
                while($row = $result_daily->fetch_assoc()) {
                    $data_daily[] = $row;
                }
            } else {
                echo "0 results_daily";
            }

            $conn->close();
            
            include "page_master/nav.php"; 
        ?>
        <h2 class="text-center mt-3 fs-1">ค่าเฉลี่ยรายวัน</h2>
        <div class="row">
            <div class="col-md-12">
                <canvas id="myChart"></canvas>
            </div>
        </div>
        <div class="row mt-5">
            <div class="value col-md-4">
                <h4>ค่าอุณหภูมิปัจจุบัน</h4>
                <p>
                    <span id="showtemp"></span>
                    <small id="valuetemp" class="fs-3">0%</small>
                </p>
                <input class="value-input" type="hidden" value="0" id="max" min="135" max="315">
            </div>
            <div class="value col-md-4">
                <h4>ค่าความชื้นปัจจุบัน</h4>
                <p>
                    <span id="showhum"></span>
                    <small id="valuehum" class="fs-3">0%</small>
                </p>
                <input class="value-input" type="hidden" value="0" id="max" min="135" max="315">
            </div>
            <div class="value col-md-4">
                <h4>ค่าดัชนีความร้อนปัจจุบัน</h4>
                <p>
                    <span id="showheat"></span>
                    <small id="valueheat" class="fs-3">0%</small>
                </p>
                <input class="value-input" type="hidden" value="0" id="max" min="135" max="315">
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-QLWkTrtA9OMhvzhqMW82j6e0+/Q5LWGtx5RHTIsNSeKFsYXhT8HtXylHQzGySFM4" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns"></script>
    <script>
        // รับข้อมูลจาก PHP กราฟรายวันนน
        const data = <?php echo json_encode($data_daily); ?>;
        const labels = data.map(row => row.date);
        const tempData = data.map(row => row.temp);
        const humidityData = data.map(row => row.humidity);
        const heatIndexData = data.map(row => row.heat_index);
        
        console.log(data);
        console.log(labels);
        console.log(tempData);
        console.log(humidityData);
        console.log(heatIndexData);

        const ctx = document.getElementById('myChart').getContext('2d');
        const myChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'อุณหภมูิ',
                        data: tempData,
                        borderColor: 'rgba(255, 192, 0, 1)',
                        borderWidth: 2,
                        fill: false
                    },
                    {
                        label: 'ความชื้นสัมพัทธ์',
                        data: humidityData,
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 2,
                        fill: false
                    },
                    {
                        label: 'ดัชนีความร้อน',
                        data: heatIndexData,
                        borderColor: 'rgba(255, 17, 0, 1)',
                        borderWidth: 2,
                        fill: false
                    }
                ]
            },
            options: {
                responsive: true,
                scales: {
                    x: {
                        type: 'time',
                        time: {
                            unit: 'day'
                        }
                    },
                    y: {
                        beginAtZero: false,
                        ticks: {
                            stepSize: 5 // ตั้งค่าความห่างของแกน y เป็น 5 หน่วย
                        }
                    }
                },
                plugins: {
                    legend: {
                        labels: {
                            font: {
                                size: 14,
                                family: 'Kanit' // เปลี่ยนเป็นฟอนต์ที่คุณต้องการ
                            }
                        }
                    }
                }
            }
        });
        //////////////////////////////////////////////////////////
        //ค่าปัจจุุบัน
        function toRangeValue(value) {
            return value * 1.8 + 135;
        }

        let temp = <?php echo json_encode($temp); ?> 
        let hum = <?php echo json_encode($hum); ?> 
        let heat = <?php echo json_encode($heat); ?> 
        let tempRangeValue = toRangeValue(temp);
        let humRangeValue = toRangeValue(hum);
        let heatRangeValue = toRangeValue(heat);


        document.getElementById('showtemp').style.transform = `rotate(${tempRangeValue}deg)`;
        document.getElementById('showhum').style.transform = `rotate(${humRangeValue}deg)`;
        document.getElementById('showheat').style.transform = `rotate(${heatRangeValue}deg)`;
        document.getElementById('valuetemp').innerText = `${((tempRangeValue - 135) / 1.8).toFixed(2)} °C`;
        document.getElementById('valuehum').innerText = `${((humRangeValue - 135) / 1.8).toFixed(2)} %`;
        document.getElementById('valueheat').innerText = `${((heatRangeValue - 135) / 1.8).toFixed(2)} °C`;
    </script>
</body>
</html>
