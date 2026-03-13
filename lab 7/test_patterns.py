import unittest
from chain_of_responsibility import SupportRequest, FAQHandler, CustomerServiceHandler, ManagerHandler, DirectorHandler
from state import OnlineOrder
from mediator import KinderStoreMediator
from template_method import SalesReport, InventoryReport
from visitor import BoardGameElement, ElectronicElement, PlushElement, CSVExportVisitor, XMLExportVisitor, TaxCalculatorVisitor


class TestChainOfResponsibility(unittest.TestCase):

    def setUp(self):
        self.faq = FAQHandler()
        self.cs = CustomerServiceHandler()
        self.mgr = ManagerHandler()
        self.director = DirectorHandler()
        self.faq.set_next(self.cs).set_next(self.mgr).set_next(self.director)

    def test_faq_handles(self):
        req = SupportRequest("program magazin", 1)
        self.faq.handle(req)
        self.assertIn("FAQ", req.response)

    def test_cs_handles(self):
        req = SupportRequest("produs defect", 2)
        self.faq.handle(req)
        self.assertIn("Serviciu Clienti", req.response)

    def test_manager_handles(self):
        req = SupportRequest("rambursare mare", 3)
        self.faq.handle(req)
        self.assertIn("Manager", req.response)

    def test_director_handles(self):
        req = SupportRequest("criza majora", 5)
        self.faq.handle(req)
        self.assertIn("Director", req.response)


class TestState(unittest.TestCase):

    def test_advance(self):
        order = OnlineOrder("ORD-001")
        self.assertEqual(order.status, "Draft")
        order.advance()
        self.assertEqual(order.status, "Confirmata")
        order.advance()
        self.assertEqual(order.status, "Ambalata")

    def test_go_back(self):
        order = OnlineOrder("ORD-001")
        order.advance()
        order.advance()
        order.go_back()
        self.assertEqual(order.status, "Confirmata")

    def test_delivered_no_advance(self):
        order = OnlineOrder("ORD-001")
        for _ in range(4):
            order.advance()
        self.assertEqual(order.status, "Livrata")
        order.advance()
        self.assertEqual(order.status, "Livrata")

    def test_history(self):
        order = OnlineOrder("ORD-001")
        order.advance()
        order.advance()
        self.assertEqual(len(order.history), 3)


class TestMediator(unittest.TestCase):

    def test_sale_updates_warehouse(self):
        mediator = KinderStoreMediator()
        mediator.warehouse.restock("Robot Dansator", 10)
        mediator.sales.new_sale("Robot Dansator", 2)
        self.assertEqual(mediator.warehouse.get_stock("Robot Dansator"), 8)

    def test_sale_records_revenue(self):
        mediator = KinderStoreMediator()
        mediator.warehouse.restock("Ursulet Teddy", 5)
        mediator.sales.new_sale("Ursulet Teddy", 1)
        self.assertAlmostEqual(mediator.accounting.revenue, 59.99)

    def test_low_stock_alert(self):
        mediator = KinderStoreMediator()
        mediator.warehouse.restock("Catan", 3)
        mediator.sales.new_sale("Catan", 2)
        alerts = [m for m in mediator.warehouse.messages if "ALERTA" in m]
        self.assertTrue(len(alerts) > 0)


class TestTemplateMethod(unittest.TestCase):

    def test_sales_report(self):
        sales = [{"toy": "Robot", "qty": 2, "total": 399.98}]
        report = SalesReport(sales).generate()
        self.assertIn("Raport Vanzari", report)
        self.assertIn("399.98", report)

    def test_inventory_report(self):
        stock = {"Robot": 10, "Ursulet": 2}
        report = InventoryReport(stock).generate()
        self.assertIn("STOC SCAZUT", report)
        self.assertIn("OK", report)


class TestVisitor(unittest.TestCase):

    def setUp(self):
        self.toys = [
            BoardGameElement("Catan", 149.99, 4),
            ElectronicElement("Robot", 199.99, "AA"),
            PlushElement("Ursulet", 59.99, "M"),
        ]

    def test_csv_export(self):
        visitor = CSVExportVisitor()
        for toy in self.toys:
            result = toy.accept(visitor)
            self.assertIn(toy.name, result)
            self.assertIn(",", result)

    def test_xml_export(self):
        visitor = XMLExportVisitor()
        for toy in self.toys:
            result = toy.accept(visitor)
            self.assertIn("<toy", result)
            self.assertIn(toy.name, result)

    def test_tax_calculator(self):
        visitor = TaxCalculatorVisitor()
        result = self.toys[1].accept(visitor)
        self.assertIn("TVA", result)


if __name__ == "__main__":
    unittest.main()
