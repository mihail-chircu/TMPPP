from abc import ABC, abstractmethod


class OrderState(ABC):
    @abstractmethod
    def next(self, order: "OnlineOrder"):
        pass

    @abstractmethod
    def prev(self, order: "OnlineOrder"):
        pass

    @abstractmethod
    def status(self) -> str:
        pass


class DraftState(OrderState):
    def next(self, order):
        order.set_state(ConfirmedState())

    def prev(self, order):
        pass

    def status(self) -> str:
        return "Draft"


class ConfirmedState(OrderState):
    def next(self, order):
        order.set_state(PackedState())

    def prev(self, order):
        order.set_state(DraftState())

    def status(self) -> str:
        return "Confirmata"


class PackedState(OrderState):
    def next(self, order):
        order.set_state(ShippedState())

    def prev(self, order):
        order.set_state(ConfirmedState())

    def status(self) -> str:
        return "Ambalata"


class ShippedState(OrderState):
    def next(self, order):
        order.set_state(DeliveredState())

    def prev(self, order):
        pass

    def status(self) -> str:
        return "Expediata"


class DeliveredState(OrderState):
    def next(self, order):
        pass

    def prev(self, order):
        pass

    def status(self) -> str:
        return "Livrata"


class OnlineOrder:
    def __init__(self, order_id: str):
        self.order_id = order_id
        self._state = DraftState()
        self._history = [self._state.status()]

    def set_state(self, state: OrderState):
        self._state = state
        self._history.append(state.status())

    def advance(self):
        self._state.next(self)

    def go_back(self):
        self._state.prev(self)

    @property
    def status(self) -> str:
        return self._state.status()

    @property
    def history(self) -> list:
        return list(self._history)
