class InventoryService:
    def __init__(self):
        self._stock = {
            "Ursulet Teddy": 10,
            "Robot Dansator": 5,
            "Colonistii din Catan": 8,
        }

    def check_availability(self, toy_name: str) -> bool:
        return self._stock.get(toy_name, 0) > 0

    def reserve(self, toy_name: str) -> bool:
        if self._stock.get(toy_name, 0) > 0:
            self._stock[toy_name] -= 1
            return True
        return False


class PricingService:
    PRICES = {
        "Ursulet Teddy": 59.99,
        "Robot Dansator": 199.99,
        "Colonistii din Catan": 149.99,
    }

    def get_price(self, toy_name: str) -> float:
        return self.PRICES.get(toy_name, 0.0)

    def apply_discount(self, price: float, discount: float) -> float:
        return price * (1 - discount)


class PaymentService:
    def process_payment(self, amount: float) -> str:
        return f"Plata de {amount:.2f} LEI procesata cu succes"


class NotificationService:
    def send_confirmation(self, toy_name: str, total: float) -> str:
        return f"Email trimis: Ati comandat '{toy_name}' - {total:.2f} LEI"


class WrappingService:
    def wrap(self, toy_name: str, gift_wrap: bool) -> str:
        if gift_wrap:
            return f"'{toy_name}' a fost ambalat cadou"
        return f"'{toy_name}' in punga standard"


class OrderFacade:
    def __init__(self):
        self._inventory = InventoryService()
        self._pricing = PricingService()
        self._payment = PaymentService()
        self._notification = NotificationService()
        self._wrapping = WrappingService()

    def place_order(self, toy_name: str, discount: float = 0.0, gift_wrap: bool = False) -> str:
        steps = []

        if not self._inventory.check_availability(toy_name):
            return f"Eroare: '{toy_name}' nu este in stoc."

        price = self._pricing.get_price(toy_name)
        final_price = self._pricing.apply_discount(price, discount)
        steps.append(f"Pret: {price:.2f} LEI -> {final_price:.2f} LEI (reducere {discount*100:.0f}%)")

        self._inventory.reserve(toy_name)
        steps.append("Stoc actualizat")

        steps.append(self._payment.process_payment(final_price))
        steps.append(self._wrapping.wrap(toy_name, gift_wrap))
        steps.append(self._notification.send_confirmation(toy_name, final_price))

        return "\n    ".join(steps)
