from datetime import date
from typing import List
from toy import Toy
from interfaces import PriceCalculator


class StandardPriceCalculator(PriceCalculator):

    def calculate(self, toys: List[Toy]) -> float:
        return sum(toy.calculate_price() for toy in toys)


class BulkDiscountCalculator(PriceCalculator):

    BULK_THRESHOLD = 5
    BULK_DISCOUNT = 0.10

    def calculate(self, toys: List[Toy]) -> float:
        subtotal = sum(toy.calculate_price() for toy in toys)
        if len(toys) >= self.BULK_THRESHOLD:
            subtotal *= (1 - self.BULK_DISCOUNT)
        return subtotal


class SeasonalDiscountCalculator(PriceCalculator):

    def __init__(self, seasonal_discount: float = 0.20):
        self._seasonal_discount = seasonal_discount

    def calculate(self, toys: List[Toy]) -> float:
        subtotal = sum(toy.calculate_price() for toy in toys)
        return subtotal * (1 - self._seasonal_discount)


class Order:

    _order_counter = 0

    def __init__(self, calculator: PriceCalculator = None):
        Order._order_counter += 1
        self._order_id = f"ORD-{Order._order_counter:04d}"
        self._items: List[Toy] = []
        self._date = date.today()
        self._calculator = calculator or StandardPriceCalculator()

    @property
    def order_id(self) -> str:
        return self._order_id

    @property
    def items(self) -> List[Toy]:
        return list(self._items)

    @property
    def order_date(self) -> date:
        return self._date

    def add_item(self, toy: Toy) -> None:
        self._items.append(toy)

    def remove_item(self, toy: Toy) -> bool:
        if toy in self._items:
            self._items.remove(toy)
            return True
        return False

    def get_total(self) -> float:
        return self._calculator.calculate(self._items)

    def __str__(self) -> str:
        lines = [f"=== Comanda {self._order_id} ({self._date}) ==="]
        for toy in self._items:
            lines.append(f"  - {toy.name}: {toy.price:.2f} LEI "
                         f"(reducere: {toy.get_discount()*100:.0f}%) "
                         f"-> {toy.calculate_price():.2f} LEI")
        lines.append(f"  TOTAL: {self.get_total():.2f} LEI")
        return "\n".join(lines)
