class ToyType:
    def __init__(self, category: str, material: str, age_group: str):
        self.category = category
        self.material = material
        self.age_group = age_group

    def __str__(self):
        return f"[{self.category}, {self.material}, {self.age_group}]"


class ToyTypeFactory:
    _types: dict = {}

    @classmethod
    def get_toy_type(cls, category: str, material: str, age_group: str) -> ToyType:
        key = f"{category}_{material}_{age_group}"
        if key not in cls._types:
            cls._types[key] = ToyType(category, material, age_group)
        return cls._types[key]

    @classmethod
    def get_count(cls) -> int:
        return len(cls._types)

    @classmethod
    def clear(cls):
        cls._types.clear()


class ToyOnShelf:
    def __init__(self, name: str, price: float, toy_type: ToyType):
        self.name = name
        self.price = price
        self.toy_type = toy_type

    def display(self) -> str:
        return f"{self.name} - {self.price:.2f} LEI {self.toy_type}"
