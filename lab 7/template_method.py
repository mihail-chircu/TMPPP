from abc import ABC, abstractmethod
from datetime import datetime


class StoreReport(ABC):
    def generate(self) -> str:
        lines = []
        lines.append(self.header())
        lines.append(self.body())
        lines.append(self.footer())
        return "\n".join(lines)

    def header(self) -> str:
        now = datetime.now().strftime("%Y-%m-%d %H:%M")
        return f"=== KINDER - {self.report_title()} ({now}) ==="

    @abstractmethod
    def report_title(self) -> str:
        pass

    @abstractmethod
    def body(self) -> str:
        pass

    def footer(self) -> str:
        return "=== Sfarsit raport ==="


class SalesReport(StoreReport):
    def __init__(self, sales: list):
        self._sales = sales

    def report_title(self) -> str:
        return "Raport Vanzari"

    def body(self) -> str:
        lines = []
        total = 0.0
        for sale in self._sales:
            lines.append(f"  {sale['toy']}: {sale['qty']}x = {sale['total']:.2f} LEI")
            total += sale["total"]
        lines.append(f"  ---")
        lines.append(f"  TOTAL VANZARI: {total:.2f} LEI")
        return "\n".join(lines)


class InventoryReport(StoreReport):
    def __init__(self, stock: dict):
        self._stock = stock

    def report_title(self) -> str:
        return "Raport Inventar"

    def body(self) -> str:
        lines = []
        for toy, qty in self._stock.items():
            status = "OK" if qty >= 5 else "STOC SCAZUT"
            lines.append(f"  {toy}: {qty} buc ({status})")
        lines.append(f"  ---")
        lines.append(f"  Total produse unice: {len(self._stock)}")
        return "\n".join(lines)


class ReturnsReport(StoreReport):
    def __init__(self, returns: list):
        self._returns = returns

    def report_title(self) -> str:
        return "Raport Retururi"

    def body(self) -> str:
        lines = []
        total = 0.0
        for ret in self._returns:
            lines.append(f"  {ret['toy']} - motiv: {ret['reason']} ({ret['amount']:.2f} LEI)")
            total += ret["amount"]
        lines.append(f"  ---")
        lines.append(f"  TOTAL RETURURI: {total:.2f} LEI ({len(self._returns)} produse)")
        return "\n".join(lines)
