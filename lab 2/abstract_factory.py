from abc import ABC, abstractmethod


class Toy(ABC):
    @abstractmethod
    def get_description(self) -> str:
        pass

    def __str__(self) -> str:
        return self.get_description()


class Box(ABC):
    @abstractmethod
    def get_description(self) -> str:
        pass

    def __str__(self) -> str:
        return self.get_description()


class GiftCard(ABC):
    @abstractmethod
    def get_message(self) -> str:
        pass

    def __str__(self) -> str:
        return self.get_message()


# Produse pentru copii mici (0-5 ani)
class ToddlerToy(Toy):
    def get_description(self) -> str:
        return "Cuburi moi colorate (0-5 ani)"


class ToddlerBox(Box):
    def get_description(self) -> str:
        return "Cutie colorata cu baloane"


class ToddlerGiftCard(GiftCard):
    def get_message(self) -> str:
        return "La multi ani, micutule!"


# Produse pentru copii (6-12 ani)
class KidToy(Toy):
    def get_description(self) -> str:
        return "Set LEGO Tehnic (6-12 ani)"


class KidBox(Box):
    def get_description(self) -> str:
        return "Cutie cu supereroi"


class KidGiftCard(GiftCard):
    def get_message(self) -> str:
        return "La multi ani, campionule!"


# Produse pentru adolescenti (13+ ani)
class TeenToy(Toy):
    def get_description(self) -> str:
        return "Drona cu camera (13+ ani)"


class TeenBox(Box):
    def get_description(self) -> str:
        return "Cutie neagra eleganta"


class TeenGiftCard(GiftCard):
    def get_message(self) -> str:
        return "Happy Birthday!"


class GiftPackageFactory(ABC):
    @abstractmethod
    def create_toy(self) -> Toy:
        pass

    @abstractmethod
    def create_box(self) -> Box:
        pass

    @abstractmethod
    def create_gift_card(self) -> GiftCard:
        pass

    def create_package(self):
        toy = self.create_toy()
        box = self.create_box()
        card = self.create_gift_card()
        return {"toy": toy, "box": box, "card": card}


class ToddlerPackageFactory(GiftPackageFactory):
    def create_toy(self) -> Toy:
        return ToddlerToy()

    def create_box(self) -> Box:
        return ToddlerBox()

    def create_gift_card(self) -> GiftCard:
        return ToddlerGiftCard()


class KidPackageFactory(GiftPackageFactory):
    def create_toy(self) -> Toy:
        return KidToy()

    def create_box(self) -> Box:
        return KidBox()

    def create_gift_card(self) -> GiftCard:
        return KidGiftCard()


class TeenPackageFactory(GiftPackageFactory):
    def create_toy(self) -> Toy:
        return TeenToy()

    def create_box(self) -> Box:
        return TeenBox()

    def create_gift_card(self) -> GiftCard:
        return TeenGiftCard()
