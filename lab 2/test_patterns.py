import unittest
from factory_method import (
    BoardGameFactory, ElectronicToyFactory, PlushFactory,
    BoardGame, ElectronicToy, Plush
)
from abstract_factory import (
    ToddlerPackageFactory, KidPackageFactory, TeenPackageFactory,
    ToddlerToy, ToddlerBox, ToddlerGiftCard,
    KidToy, KidBox, KidGiftCard,
    TeenToy, TeenBox, TeenGiftCard
)


class TestFactoryMethod(unittest.TestCase):

    def test_board_game_factory(self):
        factory = BoardGameFactory()
        toy = factory.create_toy()
        self.assertIsInstance(toy, BoardGame)
        self.assertEqual(toy.name, "Colonistii din Catan")

    def test_electronic_toy_factory(self):
        factory = ElectronicToyFactory()
        toy = factory.create_toy()
        self.assertIsInstance(toy, ElectronicToy)
        self.assertEqual(toy.name, "Robot Dansator")

    def test_plush_factory(self):
        factory = PlushFactory()
        toy = factory.create_toy()
        self.assertIsInstance(toy, Plush)
        self.assertEqual(toy.name, "Ursulet Teddy")

    def test_order_toy(self):
        factory = BoardGameFactory()
        toy = factory.order_toy()
        self.assertIsNotNone(toy)


class TestAbstractFactory(unittest.TestCase):

    def test_toddler_package(self):
        factory = ToddlerPackageFactory()
        package = factory.create_package()
        self.assertIsInstance(package["toy"], ToddlerToy)
        self.assertIsInstance(package["box"], ToddlerBox)
        self.assertIsInstance(package["card"], ToddlerGiftCard)

    def test_kid_package(self):
        factory = KidPackageFactory()
        package = factory.create_package()
        self.assertIsInstance(package["toy"], KidToy)
        self.assertIsInstance(package["box"], KidBox)
        self.assertIsInstance(package["card"], KidGiftCard)

    def test_teen_package(self):
        factory = TeenPackageFactory()
        package = factory.create_package()
        self.assertIsInstance(package["toy"], TeenToy)
        self.assertIsInstance(package["box"], TeenBox)
        self.assertIsInstance(package["card"], TeenGiftCard)

    def test_factory_consistency(self):
        for factory_cls in [ToddlerPackageFactory, KidPackageFactory, TeenPackageFactory]:
            factory = factory_cls()
            pkg1 = factory.create_package()
            pkg2 = factory.create_package()
            self.assertEqual(type(pkg1["toy"]), type(pkg2["toy"]))


if __name__ == "__main__":
    unittest.main()
