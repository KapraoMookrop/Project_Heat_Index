def calculate_heat_index(temperature, humidity):
    # Constants for heat index calculation
    c1 = -42.379
    c2 = 2.04901523
    c3 = 10.14333127
    c4 = -0.22475541
    c5 = -6.83783e-3
    c6 = -5.481717e-2
    c7 = 1.22874e-3
    c8 = 8.5282e-4
    c9 = -1.99e-6

    # Convert temperature from Celsius to Fahrenheit
    temperature_fahrenheit = temperature * 9 / 5 + 32

    # Calculate heat index in Fahrenheit
    heat_index_fahrenheit = (c1 + c2 * temperature_fahrenheit + c3 * humidity + 
                             c4 * temperature_fahrenheit * humidity +
                             c5 * temperature_fahrenheit ** 2 + 
                             c6 * humidity ** 2 +
                             c7 * temperature_fahrenheit ** 2 * humidity +
                             c8 * temperature_fahrenheit * humidity ** 2 +
                             c9 * temperature_fahrenheit ** 2 * humidity ** 2)

    # Convert heat index from Fahrenheit to Celsius
    heat_index_celsius = (heat_index_fahrenheit - 32) * 5 / 9

    return heat_index_celsius

# Loop to continuously receive input and calculate heat index
while True:
    try:
        temperature = float(input("Enter the temperature in Celsius: "))
        humidity = float(input("Enter the humidity in percentage: "))

        heat_index = calculate_heat_index(temperature, humidity)
        print(f"The heat index is: {heat_index:.2f} °C")

    except ValueError:
        print("Invalid input. Please enter numerical values for temperature and humidity.")
