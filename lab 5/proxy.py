from abc import ABC, abstractmethod


class ToyService(ABC):
    @abstractmethod
    def get_toy_info(self, toy_id: str) -> str:
        pass

    @abstractmethod
    def update_price(self, toy_id: str, new_price: float) -> str:
        pass


class RealToyService(ToyService):
    def __init__(self):
        self._toys = {
            "BG001": {"name": "Colonistii din Catan", "price": 149.99},
            "ET001": {"name": "Robot Dansator", "price": 199.99},
            "PL001": {"name": "Ursulet Teddy", "price": 59.99},
        }

    def get_toy_info(self, toy_id: str) -> str:
        toy = self._toys.get(toy_id)
        if toy:
            return f"{toy['name']} - {toy['price']:.2f} LEI"
        return "Jucarie negasita"

    def update_price(self, toy_id: str, new_price: float) -> str:
        if toy_id in self._toys:
            old = self._toys[toy_id]["price"]
            self._toys[toy_id]["price"] = new_price
            return f"Pret actualizat: {old:.2f} -> {new_price:.2f} LEI"
        return "Jucarie negasita"


class CachingProxy(ToyService):
    def __init__(self, service: ToyService):
        self._service = service
        self._cache = {}

    def get_toy_info(self, toy_id: str) -> str:
        if toy_id in self._cache:
            return f"[CACHE] {self._cache[toy_id]}"
        result = self._service.get_toy_info(toy_id)
        self._cache[toy_id] = result
        return f"[DB] {result}"

    def update_price(self, toy_id: str, new_price: float) -> str:
        self._cache.pop(toy_id, None)
        return self._service.update_price(toy_id, new_price)


class AccessControlProxy(ToyService):
    def __init__(self, service: ToyService, role: str):
        self._service = service
        self._role = role

    def get_toy_info(self, toy_id: str) -> str:
        return self._service.get_toy_info(toy_id)

    def update_price(self, toy_id: str, new_price: float) -> str:
        if self._role != "admin":
            return f"Acces refuzat: rolul '{self._role}' nu poate modifica preturile"
        return self._service.update_price(toy_id, new_price)


class LoggingProxy(ToyService):
    def __init__(self, service: ToyService):
        self._service = service
        self.logs = []

    def get_toy_info(self, toy_id: str) -> str:
        self.logs.append(f"GET {toy_id}")
        return self._service.get_toy_info(toy_id)

    def update_price(self, toy_id: str, new_price: float) -> str:
        self.logs.append(f"UPDATE {toy_id} -> {new_price}")
        return self._service.update_price(toy_id, new_price)
