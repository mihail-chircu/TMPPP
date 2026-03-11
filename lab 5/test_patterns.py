import unittest
from flyweight import ToyTypeFactory, ToyOnShelf
from decorator import BasicNotification, EmailDecorator, SMSDecorator, PushDecorator
from bridge import PhoneDisplay, WebDisplay, ListCatalogView, GridCatalogView
from proxy import RealToyService, CachingProxy, AccessControlProxy, LoggingProxy


class TestFlyweight(unittest.TestCase):

    def setUp(self):
        ToyTypeFactory.clear()

    def test_shared_instances(self):
        t1 = ToyTypeFactory.get_toy_type("Plus", "bumbac", "3+")
        t2 = ToyTypeFactory.get_toy_type("Plus", "bumbac", "3+")
        self.assertIs(t1, t2)

    def test_different_types(self):
        ToyTypeFactory.get_toy_type("Plus", "bumbac", "3+")
        ToyTypeFactory.get_toy_type("Electronic", "plastic", "6+")
        self.assertEqual(ToyTypeFactory.get_count(), 2)

    def test_toy_on_shelf(self):
        tt = ToyTypeFactory.get_toy_type("Plus", "bumbac", "3+")
        toy = ToyOnShelf("Ursulet", 59.99, tt)
        self.assertIn("Ursulet", toy.display())


class TestDecorator(unittest.TestCase):

    def test_basic(self):
        n = BasicNotification("client@mail.com")
        self.assertIn("client@mail.com", n.send("Comanda confirmata"))

    def test_email_sms(self):
        n = EmailDecorator(SMSDecorator(BasicNotification("client")))
        result = n.send("Test")
        self.assertIn("Email", result)
        self.assertIn("SMS", result)

    def test_all_decorators(self):
        n = PushDecorator(EmailDecorator(SMSDecorator(BasicNotification("client"))))
        result = n.send("Test")
        self.assertIn("Push", result)
        self.assertIn("Email", result)
        self.assertIn("SMS", result)


class TestBridge(unittest.TestCase):

    def test_list_phone(self):
        view = ListCatalogView(PhoneDisplay(), ["Robot", "Ursulet"])
        self.assertIn("Telefon", view.show())
        self.assertIn("Robot", view.show())

    def test_grid_web(self):
        view = GridCatalogView(WebDisplay(), ["Robot", "Ursulet"])
        self.assertIn("Web", view.show())
        self.assertIn("Grid", view.show())


class TestProxy(unittest.TestCase):

    def test_caching(self):
        service = CachingProxy(RealToyService())
        r1 = service.get_toy_info("BG001")
        r2 = service.get_toy_info("BG001")
        self.assertIn("[DB]", r1)
        self.assertIn("[CACHE]", r2)

    def test_access_denied(self):
        service = AccessControlProxy(RealToyService(), "guest")
        result = service.update_price("BG001", 100.0)
        self.assertIn("Acces refuzat", result)

    def test_access_admin(self):
        service = AccessControlProxy(RealToyService(), "admin")
        result = service.update_price("BG001", 100.0)
        self.assertIn("actualizat", result)

    def test_logging(self):
        service = LoggingProxy(RealToyService())
        service.get_toy_info("BG001")
        service.update_price("ET001", 150.0)
        self.assertEqual(len(service.logs), 2)


if __name__ == "__main__":
    unittest.main()
