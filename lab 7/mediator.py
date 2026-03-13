from abc import ABC, abstractmethod


class StoreMediator(ABC):
    @abstractmethod
    def notify(self, sender, event: str, data: dict):
        pass


class Department:
    def __init__(self, name: str, mediator: StoreMediator = None):
        self.name = name
        self._mediator = mediator
        self.messages = []

    def set_mediator(self, mediator: StoreMediator):
        self._mediator = mediator

    def send(self, event: str, data: dict):
        self._mediator.notify(self, event, data)

    def receive(self, message: str):
        self.messages.append(message)


class SalesDepartment(Department):
    def __init__(self, mediator=None):
        super().__init__("Vanzari", mediator)

    def new_sale(self, toy: str, qty: int):
        self.send("sale", {"toy": toy, "qty": qty})


class WarehouseDepartment(Department):
    def __init__(self, mediator=None):
        super().__init__("Depozit", mediator)
        self._stock = {}

    def restock(self, toy: str, qty: int):
        self._stock[toy] = self._stock.get(toy, 0) + qty
        self.send("restock", {"toy": toy, "qty": qty})

    def reduce_stock(self, toy: str, qty: int):
        self._stock[toy] = self._stock.get(toy, 0) - qty

    def get_stock(self, toy: str) -> int:
        return self._stock.get(toy, 0)


class AccountingDepartment(Department):
    def __init__(self, mediator=None):
        super().__init__("Contabilitate", mediator)
        self.revenue = 0.0

    def record_sale(self, amount: float):
        self.revenue += amount


class KinderStoreMediator(StoreMediator):
    def __init__(self):
        self.sales = SalesDepartment(self)
        self.warehouse = WarehouseDepartment(self)
        self.accounting = AccountingDepartment(self)

    PRICES = {
        "Robot Dansator": 199.99,
        "Ursulet Teddy": 59.99,
        "Catan": 149.99,
    }

    def notify(self, sender, event: str, data: dict):
        if event == "sale":
            toy, qty = data["toy"], data["qty"]
            self.warehouse.reduce_stock(toy, qty)
            self.warehouse.receive(f"Scos din stoc: {qty}x {toy}")

            price = self.PRICES.get(toy, 0) * qty
            self.accounting.record_sale(price)
            self.accounting.receive(f"Inregistrat vanzare: {price:.2f} LEI")

            if self.warehouse.get_stock(toy) < 3:
                self.warehouse.receive(f"ALERTA: stoc scazut pentru {toy}!")

        elif event == "restock":
            toy, qty = data["toy"], data["qty"]
            self.sales.receive(f"Disponibil: {qty}x {toy} in stoc")
