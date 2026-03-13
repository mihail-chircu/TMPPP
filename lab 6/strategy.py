from abc import ABC, abstractmethod


class SortStrategy(ABC):
    @abstractmethod
    def sort(self, toys: list) -> list:
        pass

    @abstractmethod
    def name(self) -> str:
        pass


class SortByPrice(SortStrategy):
    def sort(self, toys: list) -> list:
        return sorted(toys, key=lambda t: t["price"])

    def name(self) -> str:
        return "Pret (crescator)"


class SortByPriceDesc(SortStrategy):
    def sort(self, toys: list) -> list:
        return sorted(toys, key=lambda t: t["price"], reverse=True)

    def name(self) -> str:
        return "Pret (descrescator)"


class SortByName(SortStrategy):
    def sort(self, toys: list) -> list:
        return sorted(toys, key=lambda t: t["name"])

    def name(self) -> str:
        return "Nume (A-Z)"


class SortByAge(SortStrategy):
    def sort(self, toys: list) -> list:
        return sorted(toys, key=lambda t: t["age"])

    def name(self) -> str:
        return "Varsta recomandata"


class ToyCatalog:
    def __init__(self):
        self._toys = []
        self._strategy = SortByName()

    def add_toy(self, name: str, price: float, age: int):
        self._toys.append({"name": name, "price": price, "age": age})

    def set_sort_strategy(self, strategy: SortStrategy):
        self._strategy = strategy

    def get_sorted(self) -> list:
        return self._strategy.sort(self._toys)

    def display(self) -> str:
        lines = [f"  Sortare: {self._strategy.name()}"]
        for toy in self.get_sorted():
            lines.append(f"    {toy['name']} - {toy['price']:.2f} LEI (varsta: {toy['age']}+)")
        return "\n".join(lines)
