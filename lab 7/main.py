from chain_of_responsibility import SupportRequest, FAQHandler, CustomerServiceHandler, ManagerHandler, DirectorHandler
from state import OnlineOrder
from mediator import KinderStoreMediator
from template_method import SalesReport, InventoryReport, ReturnsReport
from visitor import BoardGameElement, ElectronicElement, PlushElement, CSVExportVisitor, XMLExportVisitor, TaxCalculatorVisitor


def pause():
    input("\n  Apasa ENTER pentru a continua...")


def citeste_int(prompt, error_msg="Valoare invalida. Introduceti un numar intreg."):
    while True:
        try:
            return int(input(prompt).strip())
        except ValueError:
            print(f"  {error_msg}")
            retry = input("  Incercati din nou? (d/n): ").strip().lower()
            if retry != "d":
                return None


def citeste_float(prompt, error_msg="Valoare invalida. Introduceti un numar."):
    while True:
        try:
            return float(input(prompt).strip())
        except ValueError:
            print(f"  {error_msg}")
            retry = input("  Incercati din nou? (d/n): ").strip().lower()
            if retry != "d":
                return None


def alege_optiune(prompt, optiuni_valide, error_msg="Optiune invalida."):
    while True:
        choice = input(prompt).strip()
        if choice in optiuni_valide:
            return choice
        print(f"  {error_msg} Optiuni valide: {', '.join(optiuni_valide)}")
        retry = input("  Incercati din nou? (d/n): ").strip().lower()
        if retry != "d":
            return None


def header(title):
    print(f"\n{'='*60}")
    print(f"  {title}")
    print(f"{'='*60}")


faq = FAQHandler()
cs = CustomerServiceHandler()
mgr = ManagerHandler()
director = DirectorHandler()
faq.set_next(cs).set_next(mgr).set_next(director)

orders = {}
order_counter = [0]

mediator = KinderStoreMediator()
mediator.warehouse.restock("Robot Dansator", 10)
mediator.warehouse.restock("Ursulet Teddy", 15)
mediator.warehouse.restock("Colonistii din Catan", 8)

sales_log = []
returns_log = []

toys_visitor = [
    BoardGameElement("Colonistii din Catan", 149.99, 4),
    ElectronicElement("Robot Dansator", 199.99, "AA"),
    PlushElement("Ursulet Teddy", 59.99, "M"),
    ElectronicElement("Mini Drona LED", 179.99, "Li-Po"),
    PlushElement("Panda Gigant", 129.99, "XL"),
]


def chain_support():
    header("CHAIN OF RESPONSIBILITY - Suport")
    issue = input("  Descrieti problema: ").strip()
    if not issue:
        print("  Descrierea nu poate fi goala.")
        pause()
        return

    severity = citeste_int("  Severitate (1-5): ")
    if severity is None:
        return

    while severity < 1 or severity > 5:
        print("  Severitatea trebuie sa fie intre 1 si 5.")
        retry = input("  Incercati din nou? (d/n): ").strip().lower()
        if retry != "d":
            return
        severity = citeste_int("  Severitate (1-5): ")
        if severity is None:
            return

    req = SupportRequest(issue, severity)
    faq.handle(req)
    print(f"\n  Rezultat: {req}")
    pause()


def state_create():
    header("STATE - Comanda noua")
    order_counter[0] += 1
    oid = f"ORD-2024-{order_counter[0]:03d}"
    order = OnlineOrder(oid)
    orders[oid] = order
    print(f"  Comanda creata: {oid}")
    print(f"  Status: {order.status}")
    pause()


def state_advance():
    header("STATE - Avanseaza comanda")
    if not orders:
        print("  Nu exista comenzi.")
        pause()
        return

    print("\n  Comenzi active:")
    oid_list = list(orders.keys())
    for i, oid in enumerate(oid_list, 1):
        print(f"    [{i}] {oid}: {orders[oid].status}")

    idx = citeste_int("\n  Alege comanda (numar): ")
    if idx is None:
        return

    while idx < 1 or idx > len(oid_list):
        print(f"  Numar invalid. Alegeti intre 1 si {len(oid_list)}.")
        retry = input("  Incercati din nou? (d/n): ").strip().lower()
        if retry != "d":
            return
        idx = citeste_int("\n  Alege comanda (numar): ")
        if idx is None:
            return

    oid = oid_list[idx - 1]
    orders[oid].advance()
    print(f"  {oid}: {orders[oid].status}")
    print(f"  Istoric: {' -> '.join(orders[oid].history)}")
    pause()


def state_back():
    header("STATE - Inapoi comanda")
    if not orders:
        print("  Nu exista comenzi.")
        pause()
        return

    print("\n  Comenzi active:")
    oid_list = list(orders.keys())
    for i, oid in enumerate(oid_list, 1):
        print(f"    [{i}] {oid}: {orders[oid].status}")

    idx = citeste_int("\n  Alege comanda (numar): ")
    if idx is None:
        return

    while idx < 1 or idx > len(oid_list):
        print(f"  Numar invalid. Alegeti intre 1 si {len(oid_list)}.")
        retry = input("  Incercati din nou? (d/n): ").strip().lower()
        if retry != "d":
            return
        idx = citeste_int("\n  Alege comanda (numar): ")
        if idx is None:
            return

    oid = oid_list[idx - 1]
    old_status = orders[oid].status
    orders[oid].go_back()
    new_status = orders[oid].status
    if old_status == new_status:
        print(f"  Nu se poate reveni din starea '{old_status}'.")
    else:
        print(f"  {oid}: {old_status} -> {new_status}")
    pause()


def state_status():
    header("STATE - Status comenzi")
    if not orders:
        print("  Nu exista comenzi.")
    else:
        for oid, order in orders.items():
            print(f"  {oid}: {order.status}")
            print(f"    Istoric: {' -> '.join(order.history)}")
    pause()


def mediator_sale():
    header("MEDIATOR - Vanzare")
    products = ["Robot Dansator", "Ursulet Teddy", "Colonistii din Catan"]
    print("\n  Produse disponibile:")
    for i, p in enumerate(products, 1):
        print(f"    [{i}] {p}")

    print("\n  Alegeti produs (numar sau nume):")
    choice = input("  Alegeti: ").strip()
    try:
        idx = int(choice) - 1
        if 0 <= idx < len(products):
            toy = products[idx]
        else:
            print("  Index invalid.")
            retry = input("  Incercati din nou? (d/n): ").strip().lower()
            if retry != "d":
                return
            toy = input("  Scrieti numele produsului: ").strip()
    except ValueError:
        toy = choice

    qty = citeste_int("  Cantitate: ")
    if qty is None:
        return

    mediator.sales.new_sale(toy, qty)
    sales_log.append({"toy": toy, "qty": qty, "total": mediator.accounting.revenue})
    print(f"\n  Vanzare inregistrata: {qty}x {toy}")
    pause()


def mediator_restock():
    header("MEDIATOR - Restock")
    toy = input("  Nume produs: ").strip()
    if not toy:
        print("  Numele nu poate fi gol.")
        pause()
        return

    qty = citeste_int("  Cantitate: ")
    if qty is None:
        return

    mediator.warehouse.restock(toy, qty)
    print(f"  Restock: {qty}x {toy}")
    pause()


def mediator_messages():
    header("MEDIATOR - Mesaje departamente")
    print("\n  Mesaje Depozit:")
    for msg in mediator.warehouse.messages:
        print(f"    {msg}")

    print(f"\n  Mesaje Contabilitate:")
    for msg in mediator.accounting.messages:
        print(f"    {msg}")

    print(f"\n  Mesaje Vanzari:")
    for msg in mediator.sales.messages:
        print(f"    {msg}")

    print(f"\n  Venit total: {mediator.accounting.revenue:.2f} LEI")
    pause()


def report_sales():
    header("TEMPLATE METHOD - Raport vanzari")
    if not sales_log:
        data = [
            {"toy": "Robot Dansator", "qty": 5, "total": 999.95},
            {"toy": "Ursulet Teddy", "qty": 12, "total": 719.88},
            {"toy": "Catan", "qty": 3, "total": 449.97},
        ]
    else:
        data = sales_log
    print(SalesReport(data).generate())
    pause()


def report_inventory():
    header("TEMPLATE METHOD - Raport inventar")
    stock = {}
    for toy in ["Robot Dansator", "Ursulet Teddy", "Colonistii din Catan"]:
        stock[toy] = mediator.warehouse.get_stock(toy)
    print(InventoryReport(stock).generate())
    pause()


def report_returns():
    header("TEMPLATE METHOD - Raport retururi")
    if not returns_log:
        data = [
            {"toy": "Robot Dansator", "reason": "defect", "amount": 199.99},
            {"toy": "Ursulet Teddy", "reason": "marime gresita", "amount": 59.99},
        ]
    else:
        data = returns_log
    print(ReturnsReport(data).generate())
    pause()


def visitor_csv():
    header("VISITOR - Export CSV")
    csv_visitor = CSVExportVisitor()
    print("  name,price,type,details")
    for toy in toys_visitor:
        print(f"  {toy.accept(csv_visitor)}")
    pause()


def visitor_xml():
    header("VISITOR - Export XML")
    xml_visitor = XMLExportVisitor()
    print("  <catalog>")
    for toy in toys_visitor:
        print(f"    {toy.accept(xml_visitor)}")
    print("  </catalog>")
    pause()


def visitor_tax():
    header("VISITOR - Calcul TVA")
    tax_visitor = TaxCalculatorVisitor()
    for toy in toys_visitor:
        print(f"  {toy.accept(tax_visitor)}")
    pause()


def show_menu():
    print()
    print("=" * 60)
    print("  KINDER - Lab 7: Chain of Resp, State, Mediator,")
    print("                   Template Method, Visitor")
    print("=" * 60)
    print("    [1]  Chain of Resp - Trimite cerere suport")
    print("    [2]  State - Creaza comanda noua")
    print("    [3]  State - Avanseaza comanda")
    print("    [4]  State - Inapoi comanda")
    print("    [5]  State - Status comenzi")
    print("    [6]  Mediator - Vanzare produs")
    print("    [7]  Mediator - Restock depozit")
    print("    [8]  Mediator - Vizualizeaza mesaje departamente")
    print("    [9]  Template Method - Raport vanzari")
    print("    [10] Template Method - Raport inventar")
    print("    [11] Template Method - Raport retururi")
    print("    [12] Visitor - Export CSV")
    print("    [13] Visitor - Export XML")
    print("    [14] Visitor - Calcul TVA")
    print("    [0]  Iesire")
    print("-" * 60)


def main():
    while True:
        show_menu()
        choice = input("  Optiunea: ").strip()

        if choice == "1":
            chain_support()
        elif choice == "2":
            state_create()
        elif choice == "3":
            state_advance()
        elif choice == "4":
            state_back()
        elif choice == "5":
            state_status()
        elif choice == "6":
            mediator_sale()
        elif choice == "7":
            mediator_restock()
        elif choice == "8":
            mediator_messages()
        elif choice == "9":
            report_sales()
        elif choice == "10":
            report_inventory()
        elif choice == "11":
            report_returns()
        elif choice == "12":
            visitor_csv()
        elif choice == "13":
            visitor_xml()
        elif choice == "14":
            visitor_tax()
        elif choice == "0":
            print("\n  La revedere!\n")
            break
        else:
            print("  Optiune invalida.")
            retry = input("  Incercati din nou? (d/n): ").strip().lower()
            if retry != "d":
                print("\n  La revedere!\n")
                break


if __name__ == "__main__":
    main()
