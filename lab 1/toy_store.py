from typing import List
from toy import Toy
from inventory import Inventory
from order import Order


class ToyStore:

    def __init__(self, name: str):
        self._name = name
        self._inventory = Inventory()
        self._orders: List[Order] = []

    @property
    def name(self) -> str:
        return self._name

    @property
    def inventory(self) -> Inventory:
        return self._inventory

    @property
    def orders(self) -> List[Order]:
        return list(self._orders)

    def add_to_inventory(self, toy: Toy, quantity: int = 1) -> None:
        self._inventory.add_toy(toy, quantity)

    def place_order(self, order: Order) -> bool:
        toy_counts = {}
        for toy in order.items:
            toy_counts[toy] = toy_counts.get(toy, 0) + 1

        for toy, needed in toy_counts.items():
            if self._inventory.get_stock(toy) < needed:
                print(f"  Stoc insuficient pentru: {toy.name} "
                      f"(necesar: {needed}, disponibil: {self._inventory.get_stock(toy)})")
                return False

        for toy, needed in toy_counts.items():
            self._inventory.remove_toy(toy, needed)

        self._orders.append(order)
        return True

    def search_toys(self, keyword: str) -> List[Toy]:
        return self._inventory.search(keyword)

    def get_report(self) -> str:
        lines = [
            f"{'='*60}",
            f"  RAPORT MAGAZIN: {self._name}",
            f"{'='*60}",
            "",
            str(self._inventory),
            "",
            f"=== COMENZI ({len(self._orders)}) ==="
        ]

        total_revenue = 0.0
        for order in self._orders:
            lines.append(str(order))
            total_revenue += order.get_total()
            lines.append("")

        lines.append(f"{'='*60}")
        lines.append(f"  Venit total din comenzi: {total_revenue:.2f} LEI")
        lines.append(f"  Valoare stoc ramas: {self._inventory.get_total_value():.2f} LEI")
        lines.append(f"{'='*60}")

        return "\n".join(lines)
