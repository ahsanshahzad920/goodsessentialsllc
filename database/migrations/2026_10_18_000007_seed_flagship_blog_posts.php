<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $now = now();

        $posts = [
            [
                'title' => 'Baby Clothes Size Chart Pakistan: 0-24 Months Explained',
                'slug' => 'baby-clothes-size-chart-pakistan-0-24-months',
                'excerpt' => 'A simple, practical guide to baby clothing sizes in Pakistan by age and weight, so you buy the right size the first time.',
                'meta_title' => 'Baby Clothes Size Chart Pakistan (0-24 Months) | Goods Essentials',
                'meta_description' => 'Confused by baby clothing sizes in Pakistan? Use this simple 0-24 month size chart by age and weight to buy the right fit, every time.',
                'body' => <<<'HTML'
<p><strong>Quick answer:</strong> Most baby clothing in Pakistan is sized by age range (like "0-3 months" or "6-12 months") rather than by exact measurements. As a rule of thumb, buy one size up if your baby is above average weight for their age, or if you want the outfit to last longer.</p>

<h2>Why baby clothing sizes feel confusing</h2>
<p>Unlike adult clothing, baby sizes are not standardized across brands. A "3-6 months" romper from one supplier may fit differently than another's, because babies grow at different rates and there's no single international sizing authority for infant wear. The most reliable way to choose a size is to combine the age label with your baby's actual weight.</p>

<h2>Baby clothing size chart by age and weight</h2>
<table>
<thead><tr><th>Age Range</th><th>Approximate Weight</th><th>Approximate Height</th></tr></thead>
<tbody>
<tr><td>Newborn (0-3 months)</td><td>2.5 - 5.5 kg</td><td>45 - 60 cm</td></tr>
<tr><td>3-6 months</td><td>5.5 - 7.5 kg</td><td>60 - 67 cm</td></tr>
<tr><td>6-9 months</td><td>7.5 - 9 kg</td><td>67 - 72 cm</td></tr>
<tr><td>9-12 months</td><td>9 - 10.5 kg</td><td>72 - 76 cm</td></tr>
<tr><td>12-18 months</td><td>10.5 - 12 kg</td><td>76 - 83 cm</td></tr>
<tr><td>18-24 months</td><td>12 - 13.5 kg</td><td>83 - 90 cm</td></tr>
</tbody>
</table>
<p>These are general averages — healthy babies can fall above or below these ranges. If your baby is between two sizes, size up rather than down, since baby clothes shrink slightly after the first few washes.</p>

<h2>How to measure your baby at home</h2>
<ol>
<li><strong>Height:</strong> Lay your baby flat on a soft surface and measure from the top of the head to the heel.</li>
<li><strong>Weight:</strong> Use a baby scale, or weigh yourself holding your baby and subtract your own weight.</li>
<li><strong>Chest:</strong> Wrap a soft tape measure around the fullest part of the chest, under the arms.</li>
</ol>

<h2>Tips for buying baby clothes in Pakistan</h2>
<ul>
<li><strong>Buy a mix of sizes for newborns.</strong> Babies grow fastest in the first 3 months, so avoid stocking up heavily on "0-3 months" items.</li>
<li><strong>Check the fabric, not just the size.</strong> Soft cotton and fleece are more forgiving around growth spurts than stiff fabrics.</li>
<li><strong>Consider the season.</strong> A winter suit bought a size up can still be worn with extra layers underneath.</li>
<li><strong>Keep your receipt and tags on.</strong> If a size doesn't work out, most stores (including ours) accept exchanges within a set window as long as the item is unused with tags attached.</li>
</ul>

<h2>Frequently asked questions</h2>
<h3>Should I buy newborn size or 0-3 months?</h3>
<p>If your baby is expected to be average weight or larger at birth, 0-3 months is usually a safer first purchase than "newborn," since many babies outgrow true newborn sizing within the first few weeks.</p>
<h3>Do baby clothes run small in Pakistan?</h3>
<p>It varies by brand and fabric. Stretchy cotton and fleece tend to be more forgiving, while stiffer fabrics can run tighter. When in doubt, size up.</p>
<h3>What if the size doesn't fit after I order online?</h3>
<p>At Goods Essentials, you can request an exchange within 14 days of delivery as long as the item is unused, in its original packaging, with tags intact. See our <a href="/return-refund-policy">Return, Refund &amp; Exchange Policy</a> for details.</p>
HTML,
            ],
            [
                'title' => 'Best Newborn Gift Ideas in Pakistan (By Budget)',
                'slug' => 'best-newborn-gift-ideas-pakistan-by-budget',
                'excerpt' => 'Thoughtful newborn gift ideas for every budget — from small welcome-baby gestures to complete gift sets for baby showers and aqiqa.',
                'meta_title' => 'Best Newborn Gift Ideas in Pakistan (By Budget) | Goods Essentials',
                'meta_description' => 'Looking for a newborn gift in Pakistan? Here are thoughtful gift ideas at every budget, from small welcome gifts to complete gift sets.',
                'body' => <<<'HTML'
<p><strong>Quick answer:</strong> The best newborn gifts combine something practical (clothing, a feeding item, or a care essential) with something a little special, like a soft toy or a gift box. What matters most is choosing safe, good-quality items the new parents will actually use.</p>

<h2>What makes a good newborn gift?</h2>
<p>New parents receive a lot of gifts in the first few weeks. The ones that get used are usually practical, in a useful size (newborns grow out of "0-3 months" fast), and made from soft, safe materials. A nicely packaged gift set also saves the giver time and looks thoughtful without extra effort.</p>

<h2>Gift ideas by budget</h2>

<h3>Under Rs. 2,000</h3>
<ul>
<li>A soft fleece romper or sleepsuit in a 3-6 month size (so it fits a little later, not just in the first days)</li>
<li>A set of baby caps, socks and mittens</li>
<li>A small soft toy, like a teddy bear, suitable from birth</li>
</ul>

<h3>Rs. 2,000 - Rs. 5,000</h3>
<ul>
<li>A newborn gift box with a few coordinated items (clothing, a bib, and a soft toy)</li>
<li>A baby bath and skin care starter set</li>
<li>A set of burp cloths and bibs in a gift-ready pack</li>
</ul>

<h3>Rs. 5,000 and above</h3>
<ul>
<li>A complete newborn gift hamper covering clothing, grooming and a keepsake item</li>
<li>A feeding essentials set, for parents who are already settling into a feeding routine</li>
<li>A larger gift box suitable for a baby shower or aqiqa celebration</li>
</ul>

<h2>Gift ideas for aqiqa</h2>
<p>For aqiqa gifts, many Pakistani families prefer something the baby can use in their first few months rather than purely decorative items — think clothing sets, soft blankets, or a well-presented gift box. A gift that's gender-appropriate (or neutral, if you're unsure) and practical is always well received.</p>

<h2>What to avoid</h2>
<ul>
<li>Clothing only in "newborn" size — it's often outgrown within weeks</li>
<li>Items with small, detachable parts for very young babies</li>
<li>Anything scented or heavily dyed, since newborn skin is sensitive</li>
</ul>

<h2>Frequently asked questions</h2>
<h3>What size should I buy for a newborn gift?</h3>
<p>Consider buying 3-6 months instead of 0-3 months, so the gift is still useful a little later, since newborns grow quickly in the first weeks.</p>
<h3>Is Cash on Delivery available for gift orders?</h3>
<p>Yes, Cash on Delivery is available for orders under Rs. 40,000 across Pakistan. See our <a href="/shipping-policy">Shipping Policy</a> for delivery charges and timelines.</p>
<h3>Can I include a gift message?</h3>
<p>Reach out to us through the <a href="/contact">Contact Us</a> page before placing your order and we'll do our best to help.</p>
HTML,
            ],
            [
                'title' => 'Diaper Bag Checklist: What to Pack for Outings',
                'slug' => 'diaper-bag-checklist-what-to-pack-for-outings',
                'excerpt' => 'A practical, printable-style checklist of what to pack in your diaper bag for short trips and longer outings with your baby.',
                'meta_title' => 'Diaper Bag Checklist for Outings in Pakistan | Goods Essentials',
                'meta_description' => 'A practical diaper bag packing checklist for outings with your baby — what to bring for short trips and longer days out.',
                'body' => <<<'HTML'
<p><strong>Quick answer:</strong> A well-packed diaper bag covers four basics: diapering, feeding, a change of clothes, and comfort items. Pack enough for the outing plus a little extra in case of delays.</p>

<h2>The core diaper bag checklist</h2>
<ul>
<li>Diapers (pack more than you think you'll need, plus a couple extra)</li>
<li>Wipes, in a resealable pack</li>
<li>A small changing pad or mat</li>
<li>Diaper rash cream, if your baby uses one</li>
<li>A few small resealable bags for soiled items</li>
</ul>

<h2>Feeding essentials</h2>
<ul>
<li>A feeding bottle and pre-measured formula, or breast milk if pumped</li>
<li>A bib or burp cloth</li>
<li>A small snack and water, once your baby is on solids</li>
<li>A muslin cloth, useful for spit-up, shade, or as a light cover</li>
</ul>

<h2>Clothing and comfort</h2>
<ul>
<li>One full change of clothes (more for longer outings or younger babies)</li>
<li>An extra layer — a light jacket or fleece suit, depending on the weather</li>
<li>A cap, and socks or mittens if it's cold</li>
<li>A small soft toy or comforter your baby is used to</li>
</ul>

<h2>Practical extras that make a difference</h2>
<ul>
<li>Hand sanitizer</li>
<li>A small first-aid item or two, such as plasters</li>
<li>Your baby's health card or vaccination record, for longer trips</li>
<li>A fully charged phone and a portable charger</li>
</ul>

<h2>Packing tips</h2>
<ol>
<li><strong>Use packing cubes or pouches</strong> to separate diapering, feeding and clothing items — it makes finding things on the go much faster.</li>
<li><strong>Keep a "stays in the bag" stock</strong> of diapers and wipes so you're not re-packing those every single time.</li>
<li><strong>Check and restock after every outing</strong>, especially diapers and wipes, so the bag is always ready.</li>
<li><strong>Choose a bag with a wipeable interior</strong> — spills and leaks happen, and an easy-to-clean lining saves effort.</li>
</ol>

<h2>Frequently asked questions</h2>
<h3>How many diapers should I pack for a half-day outing?</h3>
<p>A good rule of thumb is one diaper per 2-3 hours, plus one or two extra in case of delays.</p>
<h3>What size diaper bag do I need?</h3>
<p>For short outings, a compact bag is enough. For longer days out, a larger diaper bag with more compartments makes it easier to separate feeding, diapering and clothing items.</p>
<h3>Do you deliver diaper bags with Cash on Delivery?</h3>
<p>Yes. Cash on Delivery is available across Pakistan for orders under Rs. 40,000, with delivery in about 1-2 working days within Karachi and 4-5 working days to other cities.</p>
HTML,
            ],
        ];

        foreach ($posts as $i => $post) {
            DB::table('blog_posts')->updateOrInsert(
                ['slug' => $post['slug']],
                array_merge($post, [
                    'author_name' => 'Goods Essentials Team',
                    'status' => 'published',
                    'published_at' => $now->copy()->subDays(2 - $i),
                    'created_at' => $now,
                    'updated_at' => $now,
                ])
            );
        }
    }

    public function down(): void
    {
        DB::table('blog_posts')->whereIn('slug', [
            'baby-clothes-size-chart-pakistan-0-24-months',
            'best-newborn-gift-ideas-pakistan-by-budget',
            'diaper-bag-checklist-what-to-pack-for-outings',
        ])->delete();
    }
};
