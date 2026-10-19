<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * TikTok ads-review content fixes: rewrite copied/garbled supplier text
     * (Electric Baby Nail Trimmer wrongly said "for Baby Adults"), restructure
     * the SW-4..SW-8 swaddle descriptions into Key Features / What's in the
     * Box / Suitable Age / Care & Safety sections, and fix the "SW-8 Welocme"
     * typo in the product name and slug (with a 301 redirect for the old URL).
     * Unknown facts are left as literal "[TODO: owner to confirm ...]" text
     * rather than invented, per the no-fabrication rule for this project.
     */
    public function up(): void
    {
        $now = now();

        $swCareNotes = '<h3>Care &amp; Safety Notes</h3>'
            . '<ul>'
            . '<li>[TODO: owner to confirm washing instructions and fabric composition]</li>'
            . '<li>Always supervise your baby while swaddled</li>'
            . '<li>Remove the swaddle once your baby shows signs of rolling over</li>'
            . '</ul>';

        $updates = [
            // id 5 & 6: SW-4 / SW-5, identical original copy (plain velvet swaddle, 0-6 months)
            5 => '<p>A soft velvet swaddle wrap designed to keep your newborn cozy and snug during rest or cuddle time. A gentle, everyday essential for the first few months.</p>'
                . '<h3>Key Features</h3>'
                . '<ul>'
                . '<li>Soft velvet fabric for gentle warmth against delicate skin</li>'
                . '<li>Wrap-style design, easy to use for swaddling or as a soft bedding layer</li>'
                . '<li>Lightweight and easy to carry along when you are out with your baby</li>'
                . '</ul>'
                . '<h3>What\'s in the Box</h3>'
                . '<p>1 x Baby Wrapping Swaddle. [TODO: owner to confirm if any additional pieces are included]</p>'
                . '<h3>Suitable Age</h3>'
                . '<p>Newborns aged 0&ndash;6 months.</p>'
                . $swCareNotes,

            6 => null, // filled below (same as 5)

            // id 7: SW-6, character print
            7 => '<p>A soft swaddle wrap with a fun character print, designed to keep your newborn cozy during cuddle time, naps, or outings.</p>'
                . '<h3>Key Features</h3>'
                . '<ul>'
                . '<li>Soft, gentle fabric designed for comfort against delicate skin</li>'
                . '<li>Cute character print design</li>'
                . '<li>Wrap-style design for swaddling or as a soft layer in the crib or pram</li>'
                . '</ul>'
                . '<h3>What\'s in the Box</h3>'
                . '<p>1 x Character Baby Wrapping Swaddle. [TODO: owner to confirm if any additional pieces are included]</p>'
                . '<h3>Suitable Age</h3>'
                . '<p>Suitable for newborns. [TODO: owner to confirm the recommended maximum age/weight]</p>'
                . $swCareNotes,

            // id 8: SW-7, velvet
            8 => '<p>A plush velvet swaddle wrap designed to keep your newborn cozy and comfortable during cuddle time, naps, or outings.</p>'
                . '<h3>Key Features</h3>'
                . '<ul>'
                . '<li>Soft, plush velvet fabric for everyday warmth</li>'
                . '<li>Wrap-style design for swaddling or as a soft bedding layer</li>'
                . '<li>Lightweight and easy to carry along when you are out with your baby</li>'
                . '</ul>'
                . '<h3>What\'s in the Box</h3>'
                . '<p>1 x Velvet Baby Wrapping Swaddle. [TODO: owner to confirm if any additional pieces are included]</p>'
                . '<h3>Suitable Age</h3>'
                . '<p>Suitable for newborns. [TODO: owner to confirm the recommended maximum age/weight]</p>'
                . $swCareNotes,

            // id 9: SW-8, 8-piece "Welcome to the World" bedding set
            9 => '<p>An 8-piece newborn bedding set with a sweet "Welcome to the World" print, designed to help your newborn rest comfortably at home or while travelling.</p>'
                . '<h3>Key Features</h3>'
                . '<ul>'
                . '<li>8-piece set for a complete, coordinated newborn bedding look</li>'
                . '<li>"Welcome to the World" print, a lovely choice for a new baby or baby shower gift</li>'
                . '<li>Soft fabric designed for everyday newborn comfort</li>'
                . '</ul>'
                . '<h3>What\'s in the Box</h3>'
                . '<p>1 x 8-piece newborn bedding set. [TODO: owner to confirm the exact 8 pieces included, e.g. pillow, blanket, bumper, fitted sheet]</p>'
                . '<h3>Suitable Age</h3>'
                . '<p>Newborn babies. [TODO: owner to confirm recommended age/weight range]</p>'
                . '<h3>Care &amp; Safety Notes</h3>'
                . '<ul>'
                . '<li>[TODO: owner to confirm washing instructions and fabric composition]</li>'
                . '<li>Follow safe-sleep guidance for your region when using bedding items with a newborn</li>'
                . '<li>Always supervise your baby and remove loose bedding from the sleep area as recommended by local health guidance</li>'
                . '</ul>',

            // id 10: Electric Baby Nail Trimmer
            10 => '<p>A gentle, electric way to trim and smooth your baby\'s nails at home. The soft spinning head buffs nails safely without the sharp edge of traditional baby nail clippers, making nail care less stressful for both of you.</p>'
                . '<h3>Key Features</h3>'
                . '<ul>'
                . '<li>Electric nail trimmer designed for babies and toddlers</li>'
                . '<li>6-in-1 set with multiple interchangeable heads for trimming and filing</li>'
                . '<li>Compact, portable design for use at home or while travelling</li>'
                . '<li>Soft, rounded trimming head designed to reduce the risk of nicks and cuts</li>'
                . '</ul>'
                . '<h3>What\'s in the Box</h3>'
                . '<ul>'
                . '<li>1 x Electric Nail Trimmer unit</li>'
                . '<li>Interchangeable grinding/trimming heads</li>'
                . '<li>[TODO: owner to confirm exact box contents and quantity of each attachment]</li>'
                . '</ul>'
                . '<h3>Suitable Age</h3>'
                . '<p>Designed for newborns, infants, and toddlers. [TODO: owner to confirm the manufacturer\'s recommended minimum/maximum age range]</p>'
                . '<h3>Care &amp; Safety Notes</h3>'
                . '<ul>'
                . '<li>Always supervise your child while trimming their nails</li>'
                . '<li>Clean the trimming head before and after each use</li>'
                . '<li>[TODO: owner to confirm battery type / charging method]</li>'
                . '<li>[TODO: owner to confirm material of the trimming heads and housing]</li>'
                . '<li>Keep out of reach of children when not in use</li>'
                . '</ul>',
        ];
        $updates[6] = $updates[5];

        foreach ($updates as $id => $description) {
            DB::table('products')->where('id', $id)->update([
                'product_description' => $description,
                'updated_at' => $now,
            ]);
        }

        // Typo fix: "Welocme" -> "Welcome", plus matching slug + redirect.
        $oldSlug = 'sw-8-welocme-to-the-world-baby-wrapping-swaddle-bed-set';
        $newSlug = 'sw-8-welcome-to-the-world-baby-wrapping-swaddle-bed-set';

        DB::table('products')->where('id', 9)->update([
            'product_title' => 'SW-8 Welcome to the World Baby Wrapping Swaddle Bed Set',
            'slug' => $newSlug,
            'updated_at' => $now,
        ]);

        DB::table('redirects')->updateOrInsert(
            ['old_path' => 'product/' . $oldSlug],
            [
                'old_path' => 'product/' . $oldSlug,
                'new_path' => 'product/' . $newSlug,
                'status_code' => 301,
                'created_at' => $now,
                'updated_at' => $now,
            ]
        );
    }

    public function down(): void
    {
        // No-op: content rewrites are not reversible to the original
        // copied/garbled supplier text, and the old slug keeps working via
        // the 301 redirect either way.
    }
};
