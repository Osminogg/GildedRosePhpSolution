<?php

declare(strict_types=1);

namespace Tests;

use GildedRose\GildedRose;
use GildedRose\Item;
use PHPUnit\Framework\TestCase;

class GildedRoseTest extends TestCase
{
    public function testSulfurasNotChange(): void
    {
        $items = [new Item('Sulfuras, Hand of Ragnaros', 0, 80)];
        $gildedRose = new GildedRose($items);
        //первый день
        $gildedRose->updateQuality();
        $this->assertSame(0, $items[0]->sell_in);
        $this->assertSame(80, $items[0]->quality);
        //второй день
        $gildedRose->updateQuality();
        $this->assertSame(0, $items[0]->sell_in);
        $this->assertSame(80, $items[0]->quality);
        //третий день
        $gildedRose->updateQuality();
        $this->assertSame(0, $items[0]->sell_in);
        $this->assertSame(80, $items[0]->quality);
    }

    public function testAgedBrieGrowing(): void
    {
        $items = [new Item('Aged Brie', 2, 0)];
        $gildedRose = new GildedRose($items);
        //первый день
        $gildedRose->updateQuality();
        $this->assertSame(1, $items[0]->sell_in);
        $this->assertSame(1, $items[0]->quality);
        //второй день
        $gildedRose->updateQuality();
        $this->assertSame(0, $items[0]->sell_in);
        $this->assertSame(2, $items[0]->quality);
        //третий день
        $gildedRose->updateQuality();
        $this->assertSame(-1, $items[0]->sell_in);
        $this->assertSame(4, $items[0]->quality);
    }

    public function testBackstagePassesGrowingBeforeConcert(): void
    {
        $items = [
            new Item('Backstage passes to a TAFKAL80ETC concert', 12, 23),
            new Item('Backstage passes to a TAFKAL80ETC concert', 7, 31),
            new Item('Backstage passes to a TAFKAL80ETC concert', 2, 44),
        ];
        $gildedRose = new GildedRose($items);
        //первый день
        $gildedRose->updateQuality();
        $this->assertSame(11, $items[0]->sell_in);
        $this->assertSame(24, $items[0]->quality);
        $this->assertSame(6, $items[1]->sell_in);
        $this->assertSame(33, $items[1]->quality);
        $this->assertSame(1, $items[2]->sell_in);
        $this->assertSame(47, $items[2]->quality);
        //второй день
        $gildedRose->updateQuality();
        $this->assertSame(10, $items[0]->sell_in);
        $this->assertSame(25, $items[0]->quality);
        $this->assertSame(5, $items[1]->sell_in);
        $this->assertSame(35, $items[1]->quality);
        $this->assertSame(0, $items[2]->sell_in);
        $this->assertSame(50, $items[2]->quality);
        //третий день
        $gildedRose->updateQuality();
        $this->assertSame(9, $items[0]->sell_in);
        $this->assertSame(27, $items[0]->quality);
        $this->assertSame(4, $items[1]->sell_in);
        $this->assertSame(38, $items[1]->quality);
        $this->assertSame(-1, $items[2]->sell_in);
        $this->assertSame(0, $items[2]->quality);
    }

    public function testConjuredLostQualityFaster(): void
    {
        $items = [new Item('Conjured', 2, 10)];
        $gildedRose = new GildedRose($items);
        //первый день
        $gildedRose->updateQuality();
        $this->assertSame(1, $items[0]->sell_in);
        $this->assertSame(8, $items[0]->quality);
        //второй день
        $gildedRose->updateQuality();
        $this->assertSame(0, $items[0]->sell_in);
        $this->assertSame(6, $items[0]->quality);
        //третий день
        $gildedRose->updateQuality();
        $this->assertSame(-1, $items[0]->sell_in);
        $this->assertSame(2, $items[0]->quality);
    }

    public function testDefaultProduct(): void
    {
        $items = [new Item('+5 Dexterity Vest', 2, 20)];
        $gildedRose = new GildedRose($items);
        //первый день
        $gildedRose->updateQuality();
        $this->assertSame(1, $items[0]->sell_in);
        $this->assertSame(19, $items[0]->quality);
        //второй день
        $gildedRose->updateQuality();
        $this->assertSame(0, $items[0]->sell_in);
        $this->assertSame(18, $items[0]->quality);
        //третий день
        $gildedRose->updateQuality();
        $this->assertSame(-1, $items[0]->sell_in);
        $this->assertSame(16, $items[0]->quality);
    }
}
