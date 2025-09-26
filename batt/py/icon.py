
from PIL import Image, ImageDraw, ImageFont

class TrayIconCreator:
    def __init__(self, font_name="Ubuntu-B.ttf", icon_size=(24, 24), font_size=24):
        self.font_name = font_name
        self.icon_size = icon_size
        self.font_size = font_size

    def create_tray_icon(self, text):
        image = Image.new("RGBA", self.icon_size, (0, 0, 0, 0))
        font = ImageFont.truetype(self.font_name, self.font_size)
        draw = ImageDraw.Draw(image)
        text_bbox = draw.textbbox((0, 0), text, font=font)
        text_width = text_bbox[2] - text_bbox[0]
        text_height = text_bbox[3] - text_bbox[1]
        text_x = (self.icon_size[0] - text_width) // 2
        text_y = (self.icon_size[1] - text_height) // 2 - 2
        draw.text((text_x, text_y), text, font=font, fill=(255, 255, 255, 255))
        return image
