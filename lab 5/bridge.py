from abc import ABC, abstractmethod


class DisplayDevice(ABC):
    @abstractmethod
    def render(self, content: str) -> str:
        pass


class PhoneDisplay(DisplayDevice):
    def render(self, content: str) -> str:
        return f"[Telefon] {content} (320x480)"


class TabletDisplay(DisplayDevice):
    def render(self, content: str) -> str:
        return f"[Tableta] {content} (768x1024)"


class WebDisplay(DisplayDevice):
    def render(self, content: str) -> str:
        return f"[Web] {content} (1920x1080)"


class CatalogView(ABC):
    def __init__(self, device: DisplayDevice):
        self._device = device

    @abstractmethod
    def show(self) -> str:
        pass


class ListCatalogView(CatalogView):
    def __init__(self, device: DisplayDevice, toys: list):
        super().__init__(device)
        self._toys = toys

    def show(self) -> str:
        content = "Lista: " + ", ".join(self._toys)
        return self._device.render(content)


class GridCatalogView(CatalogView):
    def __init__(self, device: DisplayDevice, toys: list):
        super().__init__(device)
        self._toys = toys

    def show(self) -> str:
        content = "Grid: [" + "] [".join(self._toys) + "]"
        return self._device.render(content)


class DetailCatalogView(CatalogView):
    def __init__(self, device: DisplayDevice, toy_name: str, price: float):
        super().__init__(device)
        self._toy_name = toy_name
        self._price = price

    def show(self) -> str:
        content = f"Detalii: {self._toy_name} - {self._price:.2f} LEI"
        return self._device.render(content)
