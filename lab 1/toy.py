from abc import abstractmethod
from interfaces import Discountable, Searchable


class Toy(Discountable, Searchable):

    def __init__(self, toy_id: str, name: str, price: float, age_recommendation: int):
        self._toy_id = toy_id
        self._name = name
        self._price = price
        self._age_recommendation = age_recommendation

    @property
    def toy_id(self) -> str:
        return self._toy_id

    @property
    def name(self) -> str:
        return self._name

    @property
    def price(self) -> float:
        return self._price

    @property
    def age_recommendation(self) -> int:
        return self._age_recommendation

    def calculate_price(self) -> float:
        return self._price * (1 - self.get_discount())

    @abstractmethod
    def get_description(self) -> str:
        pass

    def matches(self, keyword: str) -> bool:
        keyword_lower = keyword.lower()
        return (keyword_lower in self._name.lower() or
                keyword_lower in self.get_description().lower())

    def __str__(self) -> str:
        return (f"[{self._toy_id}] {self._name} - {self._price:.2f} LEI "
                f"(varsta: {self._age_recommendation}+) | {self.get_description()}")

    def __eq__(self, other):
        if isinstance(other, Toy):
            return self._toy_id == other._toy_id
        return False

    def __hash__(self):
        return hash(self._toy_id)


class BoardGame(Toy):

    def __init__(self, toy_id: str, name: str, price: float,
                 age_recommendation: int, min_players: int, max_players: int):
        super().__init__(toy_id, name, price, age_recommendation)
        self._min_players = min_players
        self._max_players = max_players

    @property
    def min_players(self) -> int:
        return self._min_players

    @property
    def max_players(self) -> int:
        return self._max_players

    def get_discount(self) -> float:
        return 0.05

    def get_description(self) -> str:
        return f"Joc de societate, {self._min_players}-{self._max_players} jucatori"

    def matches(self, keyword: str) -> bool:
        if super().matches(keyword):
            return True
        return keyword.lower() in "joc societate board game"


class ElectronicToy(Toy):

    def __init__(self, toy_id: str, name: str, price: float,
                 age_recommendation: int, battery_type: str, has_sounds: bool):
        super().__init__(toy_id, name, price, age_recommendation)
        self._battery_type = battery_type
        self._has_sounds = has_sounds

    @property
    def battery_type(self) -> str:
        return self._battery_type

    @property
    def has_sounds(self) -> bool:
        return self._has_sounds

    def get_discount(self) -> float:
        return 0.10

    def get_description(self) -> str:
        sounds = "cu sunete" if self._has_sounds else "fara sunete"
        return f"Jucarie electronica, baterii {self._battery_type}, {sounds}"

    def matches(self, keyword: str) -> bool:
        if super().matches(keyword):
            return True
        keyword_lower = keyword.lower()
        return (keyword_lower in self._battery_type.lower() or
                keyword_lower in "electronic baterii")


class Plush(Toy):

    VALID_SIZES = ("S", "M", "L", "XL")

    def __init__(self, toy_id: str, name: str, price: float,
                 age_recommendation: int, material: str, size: str):
        super().__init__(toy_id, name, price, age_recommendation)
        self._material = material
        if size.upper() not in self.VALID_SIZES:
            raise ValueError(f"Marimea trebuie sa fie una din: {self.VALID_SIZES}")
        self._size = size.upper()

    @property
    def material(self) -> str:
        return self._material

    @property
    def size(self) -> str:
        return self._size

    def get_discount(self) -> float:
        if self._size in ("L", "XL"):
            return 0.15
        return 0.0

    def get_description(self) -> str:
        return f"Jucarie de plus, material: {self._material}, marime: {self._size}"

    def matches(self, keyword: str) -> bool:
        if super().matches(keyword):
            return True
        keyword_lower = keyword.lower()
        return (keyword_lower in self._material.lower() or
                keyword_lower in "plus plush")
