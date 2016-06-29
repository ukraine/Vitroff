<?

// ïîäãðóçêà îáùèõ äëÿ àäìèíêè è âèòðèíû ôóíêöèé
include "../lib/shared.php";

// îáùèå ôóíêöèè äëÿ ðàáîòû ñ mysql (insert, update, delete)
include "../lib/default.func.php";

// Èíèöèàëèçàöèÿ àäìèíêè
include "lib/lib.php";

// Îáðàáîò÷èê ñîáûòèé, ïåðåäàííûõ ÷åðåç GET/POST
include "lib/default.php";

// ïîäêëþ÷åíèå øàáëîíà âíåøíåãî âèäà (åñòü äâà øàáëîíà)
include "tpl/$pagetemplate.html";

?>