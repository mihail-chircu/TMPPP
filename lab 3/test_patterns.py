import unittest
from builder import BirthdayPackageBuilder, ChristmasPackageBuilder, EconomyPackageBuilder, GiftDirector
from prototype import ToyPrototype, ToyRegistry
from singleton import StoreConfig


class TestBuilder(unittest.TestCase):

    def test_full_birthday_package(self):
        builder = BirthdayPackageBuilder()
        director = GiftDirector(builder)
        pkg = director.build_full_package()
        self.assertEqual(pkg.toy_name, "Ursulet Teddy XL")
        self.assertIsNotNone(pkg.wrapping)
        self.assertIsNotNone(pkg.ribbon)
        self.assertIsNotNone(pkg.gift_card_message)
        self.assertTrue(len(pkg.extras) > 0)

    def test_minimal_package(self):
        builder = EconomyPackageBuilder()
        director = GiftDirector(builder)
        pkg = director.build_minimal_package()
        self.assertEqual(pkg.toy_name, "Masinuta metalica")
        self.assertIsNotNone(pkg.wrapping)
        self.assertIsNone(pkg.ribbon)

    def test_christmas_package(self):
        builder = ChristmasPackageBuilder()
        director = GiftDirector(builder)
        pkg = director.build_full_package()
        self.assertIn("LEGO", pkg.toy_name)


class TestPrototype(unittest.TestCase):

    def test_deep_clone(self):
        original = ToyPrototype("Robot", 199.99, "Electronic", {"baterii": "AA", "culori": ["rosu", "albastru"]})
        clone = original.clone_deep()
        clone.name = "Robot V2"
        clone.attributes["culori"].append("verde")
        self.assertEqual(original.name, "Robot")
        self.assertEqual(len(original.attributes["culori"]), 2)

    def test_shallow_clone(self):
        original = ToyPrototype("Robot", 199.99, "Electronic", {"baterii": "AA", "culori": ["rosu"]})
        clone = original.clone_shallow()
        clone.attributes["culori"].append("verde")
        self.assertEqual(len(original.attributes["culori"]), 2)

    def test_registry(self):
        registry = ToyRegistry()
        registry.register("robot", ToyPrototype("Robot", 199.99, "Electronic", {"baterii": "AA"}))
        clone = registry.clone("robot")
        self.assertEqual(clone.name, "Robot")
        self.assertIn("robot", registry.list_prototypes())


class TestSingleton(unittest.TestCase):

    def test_same_instance(self):
        config1 = StoreConfig()
        config2 = StoreConfig()
        self.assertIs(config1, config2)

    def test_store_name(self):
        config = StoreConfig()
        self.assertEqual(config.store_name, "KINDER")

    def test_settings(self):
        config = StoreConfig()
        config.set_setting("theme", "dark")
        config2 = StoreConfig()
        self.assertEqual(config2.get_setting("theme"), "dark")

    def test_thread_safety(self):
        import threading
        instances = []

        def get_instance():
            instances.append(id(StoreConfig()))

        threads = [threading.Thread(target=get_instance) for _ in range(10)]
        for t in threads:
            t.start()
        for t in threads:
            t.join()

        self.assertEqual(len(set(instances)), 1)


if __name__ == "__main__":
    unittest.main()
