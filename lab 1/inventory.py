from typing import Dict, List
from toy import Toy


class Inventory:

    def __init__(self):
        self._items: Dict[Toy, int] = {}

    def add_toy(self, toy: Toy, quantity: int = 1) -> None:
        if quantity <= 0:
            raise ValueError("Cantitatea trebuie sa fie pozitiva.")
        self._items[toy] = self._items.get(toy, 0) + quantity

    def remove_toy(self, toy: Toy, quantity: int = 1) -> bool:
        if quantity <= 0:
            raise ValueError("Cantitatea trebuie sa fie pozitiva.")
        current = self._items.get(toy, 0)
        if current < quantity:
            return False
        self._items[toy] = current - quantity
        if self._items[toy] == 0:
            del self._items[toy]
        return True

    def get_stock(self, toy: Toy) -> int:
        return self._items.get(toy, 0)

    def search(self, keyword: str) -> List[Toy]:
        return [toy for toy in self._items.keys() if toy.matches(keyword)]

    def get_all_toys(self) -> Dict[Toy, int]:
        return dict(self._items)

    def get_total_value(self) -> float:
        return sum(toy.price * qty for toy, qty in self._items.items())

    def __str__(self) -> str:
        if not self._items:
            return "Inventarul este gol."
        lines = ["=== INVENTAR ==="]
        for toy, qty in self._items.items():
            lines.append(f"  {toy} | Stoc: {qty}")
        lines.append(f"  Valoare totala stoc: {self.get_total_value():.2f} LEI")
        return "\n".join(lines)
