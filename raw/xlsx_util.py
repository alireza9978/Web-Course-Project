import pandas as pd

states = pd.read_excel("./coordinates_states.xls")
cities = pd.read_excel("./cities.xls")

states = states.set_index("StateCode")
cities = cities.set_index("CityName")

cities = cities.join(states)
cities.reset_index().to_excel("./coordinates_cities.xls")