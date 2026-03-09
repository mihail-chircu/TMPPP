from abc import ABC, abstractmethod


class Toy(ABC):
    def __init__(self, name: str, price: float):
        self._name = name
        self._price = price

    @property
    def name(self) -> str:
        return self._name

    @property
    def price(self) -> float:
        return self._price

    @abstractmethod
    def get_description(self) -> str:
        pass

    def __str__(self) -> str:
        return f"{self._name} - {self._price:.2f} LEI | {self.get_description()}"


class BoardGame(Toy):
    def __init__(self, name: str, price: float, min_players: int, max_players: int):
        super().__init__(name, price)
        self._min_players = min_players
        self._max_players = max_players

    def get_description(self) -> str:
        return f"Joc de societate, {self._min_players}-{self._max_players} jucatori"


class ElectronicToy(Toy):
    def __init__(self, name: str, price: float, battery_type: str):
        super().__init__(name, price)
        self._battery_type = battery_type

    def get_description(self) -> str:
        return f"Jucarie electronica, baterii {self._battery_type}"


class Plush(Toy):
    def __init__(self, name: str, price: float, size: str):
        super().__init__(name, price)
        self._size = size

    def get_description(self) -> str:
        return f"Jucarie de plus, marime {self._size}"


class ToyFactory(ABC):
    @abstractmethod
    def create_toy(self) -> Toy:
        pass

    def order_toy(self) -> Toy:
        toy = self.create_toy()
        print(f"  Comanda procesata: {toy}")
        return toy


class BoardGameFactory(ToyFactory):
    def create_toy(self) -> Toy:
        return BoardGame("Colonistii din Catan", 149.99, 3, 4)


class ElectronicToyFactory(ToyFactory):
    def create_toy(self) -> Toy:
        return ElectronicToy("Robot Dansator", 199.99, "AA")


class PlushFactory(ToyFactory):
    def create_toy(self) -> Toy:
        return Plush("Ursulet Teddy", 59.99, "M")
