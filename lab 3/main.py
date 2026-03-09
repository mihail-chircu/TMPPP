from builder import BirthdayPackageBuilder, ChristmasPackageBuilder, EconomyPackageBuilder, GiftDirector
from prototype import ToyPrototype, ToyRegistry
from singleton import StoreConfig


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


def init_registry():
    registry = ToyRegistry()
    registry.register("robot", ToyPrototype("Robot Dansator", 199.99, "electronic", {"baterie": "AA", "sunete": True}))
    registry.register("ursulet", ToyPrototype("Ursulet Teddy", 59.99, "plus", {"material": "bumbac", "marime": "M"}))
    registry.register("catan", ToyPrototype("Colonistii din Catan", 149.99, "board_game", {"jucatori": "3-4"}))
    return registry


def builder_pachet_complet():
    header("BUILDER - Pachet complet")
    print("\n  Tipuri de pachete:")
    print("    [1] Pachet Zi de Nastere")
    print("    [2] Pachet Craciun")
    print("    [3] Pachet Economic")

    tip = alege_optiune("\n  Alege tipul [1/2/3]: ", ["1", "2", "3"])
    if tip is None:
        return

    builders = {
        "1": ("Zi de Nastere", BirthdayPackageBuilder()),
        "2": ("Craciun", ChristmasPackageBuilder()),
        "3": ("Economic", EconomyPackageBuilder()),
    }

    label, builder = builders[tip]
    director = GiftDirector(builder)
    package = director.build_full_package()

    print(f"\n  Pachet complet: {label}\n")
    print(f"  {package}")
    pause()


def builder_pachet_minimal():
    header("BUILDER - Pachet minimal")
    print("\n  Tipuri de pachete:")
    print("    [1] Pachet Zi de Nastere")
    print("    [2] Pachet Craciun")
    print("    [3] Pachet Economic")

    tip = alege_optiune("\n  Alege tipul [1/2/3]: ", ["1", "2", "3"])
    if tip is None:
        return

    builders = {
        "1": ("Zi de Nastere", BirthdayPackageBuilder()),
        "2": ("Craciun", ChristmasPackageBuilder()),
        "3": ("Economic", EconomyPackageBuilder()),
    }

    label, builder = builders[tip]
    director = GiftDirector(builder)
    package = director.build_minimal_package()

    print(f"\n  Pachet minimal: {label}\n")
    print(f"  {package}")
    pause()


def prototype_lista(registry):
    header("PROTOTYPE - Prototipuri inregistrate")
    keys = registry.list_prototypes()
    if not keys:
        print("  Niciun prototip inregistrat.")
    else:
        for i, key in enumerate(keys, 1):
            clone = registry.clone(key)
            print(f"    [{i}] {key:<15} -> {clone}")
    pause()


def prototype_cloneaza(registry):
    header("PROTOTYPE - Cloneaza jucarie")
    keys = registry.list_prototypes()
    if not keys:
        print("  Niciun prototip disponibil.")
        pause()
        return

    print("\n  Prototipuri disponibile:")
    for i, key in enumerate(keys, 1):
        clone = registry.clone(key)
        print(f"    [{i}] {key:<15} -> {clone.name} ({clone.category}) - {clone.price:.2f} LEI")

    idx = citeste_int("\n  Numarul prototipului de clonat: ")
    if idx is None:
        return

    while idx < 1 or idx > len(keys):
        print(f"  Index invalid. Alegeti intre 1 si {len(keys)}.")
        retry = input("  Incercati din nou? (d/n): ").strip().lower()
        if retry != "d":
            return
        idx = citeste_int("\n  Numarul prototipului de clonat: ")
        if idx is None:
            return

    selected_key = keys[idx - 1]

    print("\n  Tip clonare:")
    print("    [1] Deep copy (copie completa)")
    print("    [2] Shallow copy (copie superficiala)")
    clone_type = alege_optiune("  Alegeti [1/2]: ", ["1", "2"])
    if clone_type is None:
        return

    deep = clone_type == "1"
    clone = registry.clone(selected_key, deep=deep)

    new_name = input(f"  Nume nou (ENTER = pastreaza '{clone.name}'): ").strip()
    if new_name:
        clone.name = new_name

    new_price_str = input(f"  Pret nou (ENTER = pastreaza {clone.price:.2f} LEI): ").strip()
    if new_price_str:
        new_price = citeste_float("  Confirmati pretul (LEI): ")
        if new_price is not None:
            clone.price = new_price

    print(f"\n  Clona creata cu succes!")
    print(f"    Nume:      {clone.name}")
    print(f"    Categorie: {clone.category}")
    print(f"    Pret:      {clone.price:.2f} LEI")
    print(f"    Atribute:  {clone.attributes}")
    print(f"    Metoda:    {'Deep copy' if deep else 'Shallow copy'}")
    pause()


def prototype_inregistreaza(registry):
    header("PROTOTYPE - Inregistreaza prototip nou")

    key = input("  Cheie unica (ex: puzzle): ").strip()
    if not key:
        print("  Cheia nu poate fi goala.")
        pause()
        return

    name = input("  Nume jucarie: ").strip()
    if not name:
        print("  Numele nu poate fi gol.")
        pause()
        return

    price = citeste_float("  Pret (LEI): ")
    if price is None:
        return

    print("  Categorie:")
    print("    [1] board_game")
    print("    [2] electronic")
    print("    [3] plus")
    cat_choice = alege_optiune("  Alegeti [1/2/3]: ", ["1", "2", "3"])
    if cat_choice is None:
        return
    categories = {"1": "board_game", "2": "electronic", "3": "plus"}
    category = categories[cat_choice]

    attributes = {}
    print("\n  Adaugati atribute (tastati 'gata' pentru a finaliza):")
    while True:
        attr_key = input("    Cheie atribut (sau 'gata'): ").strip()
        if attr_key.lower() == "gata" or attr_key == "":
            break
        attr_val = input(f"    Valoare pentru '{attr_key}': ").strip()
        attributes[attr_key] = attr_val

    proto = ToyPrototype(name, price, category, attributes)
    registry.register(key, proto)

    print(f"\n  Prototipul '{key}' inregistrat cu succes!")
    print(f"    {proto}")
    pause()


def singleton_configuratie():
    header("SINGLETON - Configuratie magazin")
    config = StoreConfig()

    print(f"\n  Nume magazin:    {config.store_name}")
    print(f"  Moneda:          {config.currency}")
    print(f"  TVA:             {config.tax_rate*100:.0f}%")
    print(f"  Reducere maxima: {config.max_discount*100:.0f}%")
    print(f"\n  Instanta: {config}")
    pause()


def singleton_modifica():
    header("SINGLETON - Modifica setare custom")
    config = StoreConfig()

    key = input("  Cheie setare: ").strip()
    if not key:
        print("  Cheia nu poate fi goala.")
        pause()
        return

    value = input(f"  Valoare pentru '{key}': ").strip()
    config.set_setting(key, value)

    print(f"\n  Setarea '{key}' = '{value}' salvata.")
    pause()


def singleton_citeste():
    header("SINGLETON - Citeste setare custom")
    config = StoreConfig()

    key = input("  Cheie setare: ").strip()
    if not key:
        print("  Cheia nu poate fi goala.")
        pause()
        return

    value = config.get_setting(key)
    if value is None:
        print(f"  Setarea '{key}' nu exista.")
    else:
        print(f"  {key} = {value}")
    pause()


def singleton_verifica_instanta():
    header("SINGLETON - Verifica instanta unica")
    c1 = StoreConfig()
    c2 = StoreConfig()
    print(f"  config1: {id(c1)}")
    print(f"  config2: {id(c2)}")
    print(f"  Sunt aceeasi instanta: {c1 is c2}")
    pause()


def show_menu():
    print()
    print("=" * 60)
    print("  KINDER - Lab 3: Builder, Prototype, Singleton")
    print("=" * 60)
    print("    [1] Builder - Pachet complet")
    print("    [2] Builder - Pachet minimal")
    print("    [3] Prototype - Lista prototipuri")
    print("    [4] Prototype - Cloneaza jucarie")
    print("    [5] Prototype - Inregistreaza prototip nou")
    print("    [6] Singleton - Configuratie magazin")
    print("    [7] Singleton - Modifica setare custom")
    print("    [8] Singleton - Citeste setare custom")
    print("    [9] Singleton - Verifica instanta unica")
    print("    [0] Iesire")
    print("-" * 60)


def main():
    registry = init_registry()

    while True:
        show_menu()
        choice = input("  Optiunea: ").strip()

        if choice == "1":
            builder_pachet_complet()
        elif choice == "2":
            builder_pachet_minimal()
        elif choice == "3":
            prototype_lista(registry)
        elif choice == "4":
            prototype_cloneaza(registry)
        elif choice == "5":
            prototype_inregistreaza(registry)
        elif choice == "6":
            singleton_configuratie()
        elif choice == "7":
            singleton_modifica()
        elif choice == "8":
            singleton_citeste()
        elif choice == "9":
            singleton_verifica_instanta()
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
