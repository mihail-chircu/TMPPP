import unittest
from strategy import ToyCatalog, SortByPrice, SortByPriceDesc, SortByName, SortByAge
from observer import ToyStoreEvents, EmailSubscriber, SMSSubscriber, DashboardLogger
from command import Inventory, AddStockCommand, RemoveStockCommand, CommandHistory
from memento import ShoppingCart, CartHistory
from iterator import Toy, ToyCollection


class TestStrategy(unittest.TestCase):

    def setUp(self):
        self.catalog = ToyCatalog()
        self.catalog.add_toy("Robot", 199.99, 5)
        self.catalog.add_toy("Ursulet", 59.99, 3)
        self.catalog.add_toy("Catan", 149.99, 10)

    def test_sort_by_price(self):
        self.catalog.set_sort_strategy(SortByPrice())
        result = self.catalog.get_sorted()
        self.assertEqual(result[0]["name"], "Ursulet")

    def test_sort_by_price_desc(self):
        self.catalog.set_sort_strategy(SortByPriceDesc())
        result = self.catalog.get_sorted()
        self.assertEqual(result[0]["name"], "Robot")

    def test_sort_by_name(self):
        self.catalog.set_sort_strategy(SortByName())
        result = self.catalog.get_sorted()
        self.assertEqual(result[0]["name"], "Catan")

    def test_sort_by_age(self):
        self.catalog.set_sort_strategy(SortByAge())
        result = self.catalog.get_sorted()
        self.assertEqual(result[0]["name"], "Ursulet")


class TestObserver(unittest.TestCase):

    def test_email_notification(self):
        store = ToyStoreEvents()
        sub = EmailSubscriber("test@mail.com")
        store.attach(sub)
        store.new_arrival("Drona", 179.99)
        self.assertEqual(len(sub.messages), 1)
        self.assertIn("Drona", sub.messages[0])

    def test_price_drop(self):
        store = ToyStoreEvents()
        sms = SMSSubscriber("+373123")
        store.attach(sms)
        store.price_drop("Robot", 199.99, 149.99)
        self.assertIn("OFERTA", sms.messages[0])

    def test_detach(self):
        store = ToyStoreEvents()
        sub = EmailSubscriber("test@mail.com")
        store.attach(sub)
        store.detach(sub)
        store.new_arrival("Test", 10.0)
        self.assertEqual(len(sub.messages), 0)


class TestCommand(unittest.TestCase):

    def test_add_and_undo(self):
        inv = Inventory()
        history = CommandHistory()
        history.execute(AddStockCommand(inv, "Robot", 5))
        self.assertEqual(inv.get_stock("Robot"), 5)
        history.undo()
        self.assertEqual(inv.get_stock("Robot"), 0)

    def test_redo(self):
        inv = Inventory()
        history = CommandHistory()
        history.execute(AddStockCommand(inv, "Robot", 3))
        history.undo()
        history.redo()
        self.assertEqual(inv.get_stock("Robot"), 3)


class TestMemento(unittest.TestCase):

    def test_save_restore(self):
        cart = ShoppingCart()
        cart.add_item("Robot", 199.99)
        snapshot = cart.save()
        cart.add_item("Ursulet", 59.99)
        self.assertAlmostEqual(cart.get_total(), 259.98)
        cart.restore(snapshot)
        self.assertAlmostEqual(cart.get_total(), 199.99)


class TestIterator(unittest.TestCase):

    def setUp(self):
        self.collection = ToyCollection()
        self.collection.add(Toy("Robot", 199.99, "Electronic"))
        self.collection.add(Toy("Ursulet", 59.99, "Plus"))
        self.collection.add(Toy("Catan", 149.99, "Board Game"))

    def test_full_iteration(self):
        count = sum(1 for _ in self.collection.iterator())
        self.assertEqual(count, 3)

    def test_category_filter(self):
        it = self.collection.category_iterator("Plus")
        items = list(it)
        self.assertEqual(len(items), 1)
        self.assertEqual(items[0].name, "Ursulet")

    def test_price_range(self):
        it = self.collection.price_range_iterator(100.0, 200.0)
        items = list(it)
        self.assertEqual(len(items), 2)


if __name__ == "__main__":
    unittest.main()
