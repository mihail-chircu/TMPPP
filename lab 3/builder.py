from abc import ABC, abstractmethod


class GiftPackage:
    def __init__(self):
        self.toy_name = None
        self.wrapping = None
        self.ribbon = None
        self.gift_card_message = None
        self.extras = []

    def __str__(self):
        parts = [f"Pachet cadou KINDER:"]
        parts.append(f"  Jucarie: {self.toy_name}")
        parts.append(f"  Ambalaj: {self.wrapping}")
        parts.append(f"  Panglica: {self.ribbon}")
        parts.append(f"  Felicitare: {self.gift_card_message}")
        if self.extras:
            parts.append(f"  Extras: {', '.join(self.extras)}")
        return "\n".join(parts)


class GiftPackageBuilder(ABC):
    def __init__(self):
        self._package = GiftPackage()

    @abstractmethod
    def set_toy(self):
        pass

    @abstractmethod
    def set_wrapping(self):
        pass

    @abstractmethod
    def set_ribbon(self):
        pass

    @abstractmethod
    def set_gift_card(self):
        pass

    @abstractmethod
    def add_extras(self):
        pass

    def get_package(self) -> GiftPackage:
        return self._package


class BirthdayPackageBuilder(GiftPackageBuilder):
    def set_toy(self):
        self._package.toy_name = "Ursulet Teddy XL"
        return self

    def set_wrapping(self):
        self._package.wrapping = "Hartie cu baloane colorate"
        return self

    def set_ribbon(self):
        self._package.ribbon = "Panglica aurie"
        return self

    def set_gift_card(self):
        self._package.gift_card_message = "La multi ani!"
        return self

    def add_extras(self):
        self._package.extras = ["Confetti", "Balon cu heliu"]
        return self


class ChristmasPackageBuilder(GiftPackageBuilder):
    def set_toy(self):
        self._package.toy_name = "Set LEGO Winter Village"
        return self

    def set_wrapping(self):
        self._package.wrapping = "Hartie rosie cu fulgi de zapada"
        return self

    def set_ribbon(self):
        self._package.ribbon = "Panglica verde cu clopotei"
        return self

    def set_gift_card(self):
        self._package.gift_card_message = "Craciun Fericit!"
        return self

    def add_extras(self):
        self._package.extras = ["Globuletz decorativ", "Acadea"]
        return self


class EconomyPackageBuilder(GiftPackageBuilder):
    def set_toy(self):
        self._package.toy_name = "Masinuta metalica"
        return self

    def set_wrapping(self):
        self._package.wrapping = "Punga simpla de hartie"
        return self

    def set_ribbon(self):
        self._package.ribbon = "Fara panglica"
        return self

    def set_gift_card(self):
        self._package.gift_card_message = "Cu drag!"
        return self

    def add_extras(self):
        self._package.extras = []
        return self


class GiftDirector:
    def __init__(self, builder: GiftPackageBuilder):
        self._builder = builder

    def build_full_package(self) -> GiftPackage:
        self._builder.set_toy()
        self._builder.set_wrapping()
        self._builder.set_ribbon()
        self._builder.set_gift_card()
        self._builder.add_extras()
        return self._builder.get_package()

    def build_minimal_package(self) -> GiftPackage:
        self._builder.set_toy()
        self._builder.set_wrapping()
        return self._builder.get_package()
