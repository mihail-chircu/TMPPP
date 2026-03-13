class Toy:
    def __init__(self, name: str, price: float, category: str):
        self.name = name
        self.price = price
        self.category = category

    def __str__(self):
        return f"{self.name} ({self.category}) - {self.price:.2f} LEI"


class ToyCollection:
    def __init__(self):
        self._toys = []

    def add(self, toy: Toy):
        self._toys.append(toy)

    def __len__(self):
        return len(self._toys)

    def iterator(self):
        return ToyIterator(self._toys)

    def price_range_iterator(self, min_price: float, max_price: float):
        filtered = [t for t in self._toys if min_price <= t.price <= max_price]
        return ToyIterator(filtered)

    def category_iterator(self, category: str):
        filtered = [t for t in self._toys if t.category == category]
        return ToyIterator(filtered)


class ToyIterator:
    def __init__(self, toys: list):
        self._toys = toys
        self._index = 0

    def __iter__(self):
        self._index = 0
        return self

    def __next__(self) -> Toy:
        if self._index >= len(self._toys):
            raise StopIteration
        toy = self._toys[self._index]
        self._index += 1
        return toy

    def has_next(self) -> bool:
        return self._index < len(self._toys)

    def reset(self):
        self._index = 0
