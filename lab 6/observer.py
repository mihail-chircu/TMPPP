from abc import ABC, abstractmethod


class Observer(ABC):
    @abstractmethod
    def update(self, event: str, data: dict):
        pass


class Subject:
    def __init__(self):
        self._observers: list[Observer] = []

    def attach(self, observer: Observer):
        self._observers.append(observer)

    def detach(self, observer: Observer):
        self._observers.remove(observer)

    def notify(self, event: str, data: dict):
        for obs in self._observers:
            obs.update(event, data)


class ToyStoreEvents(Subject):
    def new_arrival(self, toy_name: str, price: float):
        self.notify("new_arrival", {"toy": toy_name, "price": price})

    def price_drop(self, toy_name: str, old_price: float, new_price: float):
        self.notify("price_drop", {"toy": toy_name, "old": old_price, "new": new_price})

    def out_of_stock(self, toy_name: str):
        self.notify("out_of_stock", {"toy": toy_name})


class EmailSubscriber(Observer):
    def __init__(self, email: str):
        self.email = email
        self.messages = []

    def update(self, event: str, data: dict):
        if event == "new_arrival":
            msg = f"[Email -> {self.email}] Nou in KINDER: {data['toy']} - {data['price']:.2f} LEI"
        elif event == "price_drop":
            msg = f"[Email -> {self.email}] Reducere: {data['toy']} {data['old']:.2f} -> {data['new']:.2f} LEI"
        elif event == "out_of_stock":
            msg = f"[Email -> {self.email}] Epuizat: {data['toy']}"
        else:
            msg = f"[Email -> {self.email}] {event}: {data}"
        self.messages.append(msg)


class SMSSubscriber(Observer):
    def __init__(self, phone: str):
        self.phone = phone
        self.messages = []

    def update(self, event: str, data: dict):
        if event == "price_drop":
            msg = f"[SMS -> {self.phone}] OFERTA: {data['toy']} acum {data['new']:.2f} LEI!"
        else:
            msg = f"[SMS -> {self.phone}] {event}: {data}"
        self.messages.append(msg)


class DashboardLogger(Observer):
    def __init__(self):
        self.log = []

    def update(self, event: str, data: dict):
        self.log.append({"event": event, "data": data})
