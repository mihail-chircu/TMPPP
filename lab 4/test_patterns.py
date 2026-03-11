import unittest
from adapter import PayPalAdapter, StripeAdapter, CashAdapter
from composite import ToyItem, ToyCategory
from facade import OrderFacade


class TestAdapter(unittest.TestCase):

    def test_paypal_adapter(self):
        processor = PayPalAdapter("client@email.com")
        result = processor.pay(149.99)
        self.assertIn("PayPal", result)
        self.assertIn("149.99", result)

    def test_stripe_adapter(self):
        processor = StripeAdapter("tok_123abc")
        result = processor.pay(199.99)
        self.assertIn("Stripe", result)
        self.assertIn("19999", result)

    def test_cash_adapter(self):
        processor = CashAdapter(200.0)
        result = processor.pay(149.99)
        self.assertIn("Cash", result)
        self.assertIn("rest", result)


class TestComposite(unittest.TestCase):

    def test_single_item(self):
        item = ToyItem("Ursulet", 59.99)
        self.assertEqual(item.get_price(), 59.99)

    def test_category_price(self):
        cat = ToyCategory("Plus")
        cat.add(ToyItem("Ursulet", 59.99))
        cat.add(ToyItem("Unicorn", 79.99))
        self.assertAlmostEqual(cat.get_price(), 139.98, places=2)

    def test_nested_categories(self):
        root = ToyCategory("Catalog")
        plus = ToyCategory("Plus")
        plus.add(ToyItem("Ursulet", 59.99))
        electronic = ToyCategory("Electronic")
        electronic.add(ToyItem("Robot", 199.99))
        root.add(plus)
        root.add(electronic)
        self.assertAlmostEqual(root.get_price(), 259.98, places=2)

    def test_display(self):
        cat = ToyCategory("Plus")
        cat.add(ToyItem("Ursulet", 59.99))
        result = cat.display()
        self.assertIn("Plus", result)
        self.assertIn("Ursulet", result)


class TestFacade(unittest.TestCase):

    def test_successful_order(self):
        facade = OrderFacade()
        result = facade.place_order("Ursulet Teddy", 0.10, True)
        self.assertIn("Plata", result)
        self.assertIn("ambalat cadou", result)

    def test_out_of_stock(self):
        facade = OrderFacade()
        result = facade.place_order("Jucarie Inexistenta")
        self.assertIn("nu este in stoc", result)

    def test_no_discount(self):
        facade = OrderFacade()
        result = facade.place_order("Robot Dansator")
        self.assertIn("199.99", result)


if __name__ == "__main__":
    unittest.main()
