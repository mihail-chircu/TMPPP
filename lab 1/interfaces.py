from abc import ABC, abstractmethod


class Discountable(ABC):
    @abstractmethod
    def get_discount(self) -> float:
        pass


class Searchable(ABC):
    @abstractmethod
    def matches(self, keyword: str) -> bool:
        pass


class PriceCalculator(ABC):
    @abstractmethod
    def calculate(self, toys: list) -> float:
        pass
