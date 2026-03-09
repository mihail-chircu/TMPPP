from factory_method import BoardGameFactory, ElectronicToyFactory, PlushFactory
from abstract_factory import ToddlerPackageFactory, KidPackageFactory, TeenPackageFactory


def pause():
    input("\n  Apasa ENTER pentru a continua...")


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


created_toys = []


def factory_comanda_jucarie():
    header("FACTORY METHOD - Comanda jucarie")
    print("\n  Tipuri de fabrica disponibile:")
    print("    [1] Joc de societate (BoardGameFactory)")
    print("    [2] Jucarie electronica (ElectronicToyFactory)")
    print("    [3] Jucarie de plus (PlushFactory)")

    tip = alege_optiune("\n  Alege fabrica [1/2/3]: ", ["1", "2", "3"])
    if tip is None:
        return

    factories = {
        "1": BoardGameFactory(),
        "2": ElectronicToyFactory(),
        "3": PlushFactory(),
    }

    factory = factories[tip]
    toy = factory.order_toy()
    created_toys.append(toy)

    print(f"\n  Jucarie creata:")
    print(f"    Tip:   {type(toy).__name__}")
    print(f"    Nume:  {toy.name}")
    print(f"    Pret:  {toy.price:.2f} LEI")
    print(f"    Desc:  {toy.get_description()}")
    pause()


def factory_toate_fabricile():
    header("FACTORY METHOD - Toate fabricile")
    print()

    factories = [
        ("BoardGameFactory", BoardGameFactory()),
        ("ElectronicToyFactory", ElectronicToyFactory()),
        ("PlushFactory", PlushFactory()),
    ]

    for label, factory in factories:
        toy = factory.order_toy()
        created_toys.append(toy)
        print(f"    {label:<25} -> {toy}")
        print()
    pause()


def factory_lista():
    header("FACTORY METHOD - Jucarii create")
    if not created_toys:
        print("  Nicio jucarie creata inca.")
    else:
        for i, toy in enumerate(created_toys, 1):
            print(f"    [{i}] {toy}")
    pause()


def abstract_pachet():
    header("ABSTRACT FACTORY - Pachet cadou")
    print("\n  Grupe de varsta:")
    print("    [1] Toddler (0-5 ani)")
    print("    [2] Kid (6-12 ani)")
    print("    [3] Teen (13+ ani)")

    tip = alege_optiune("\n  Alege grupa [1/2/3]: ", ["1", "2", "3"])
    if tip is None:
        return

    factories = {
        "1": ("Toddler (0-5 ani)", ToddlerPackageFactory()),
        "2": ("Kid (6-12 ani)", KidPackageFactory()),
        "3": ("Teen (13+ ani)", TeenPackageFactory()),
    }

    label, factory = factories[tip]
    package = factory.create_package()

    print(f"\n  Pachet cadou: {label}\n")
    print(f"    Jucarie:    {package['toy']}")
    print(f"    Cutie:      {package['box']}")
    print(f"    Felicitare: {package['card']}")
    pause()


def abstract_toate_pachetele():
    header("ABSTRACT FACTORY - Toate pachetele")
    print()

    all_factories = [
        ("Toddler (0-5 ani)", ToddlerPackageFactory()),
        ("Kid (6-12 ani)", KidPackageFactory()),
        ("Teen (13+ ani)", TeenPackageFactory()),
    ]

    for label, factory in all_factories:
        package = factory.create_package()
        print(f"  --- Pachet: {label} ---")
        print(f"    Jucarie:    {package['toy']}")
        print(f"    Cutie:      {package['box']}")
        print(f"    Felicitare: {package['card']}")
        print()
    pause()


def show_menu():
    print()
    print("=" * 60)
    print("  KINDER - Lab 2: Factory Method & Abstract Factory")
    print("=" * 60)
    print("    [1] Factory Method - Comanda jucarie")
    print("    [2] Factory Method - Toate fabricile")
    print("    [3] Factory Method - Lista jucarii create")
    print("    [4] Abstract Factory - Pachet cadou pe varsta")
    print("    [5] Abstract Factory - Toate pachetele")
    print("    [0] Iesire")
    print("-" * 60)


def main():
    while True:
        show_menu()
        choice = input("  Optiunea: ").strip()

        if choice == "1":
            factory_comanda_jucarie()
        elif choice == "2":
            factory_toate_fabricile()
        elif choice == "3":
            factory_lista()
        elif choice == "4":
            abstract_pachet()
        elif choice == "5":
            abstract_toate_pachetele()
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
