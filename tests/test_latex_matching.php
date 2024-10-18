<?php
include("../scripts/latex_utils.php");

$str = 'This is a backslash: \\\n'; // Added missing semicolon here
echo $str;


$text = '<p>这是一个公式 $E=mc^2$ 和一个 display 公式 $$\int_0^1 x^2 dx$$ 以及一个环境公式 </p>
<p>\begin{equation}<br />
	\begin{split}<br />
	\cos 2x &#038;= \cos^2 x &#8211; \sin^2 x\\\\<br />
	&#038;= 2\cos^2 x &#8211; 1<br />
	\end{split}<br />
\end{equation}</p>
<p>and a new formula \[\sum_{i=1}^n i^2\]</p>';

$restored_text = restore_latex_in_text($text);

echo "\nRestored Text:\n";
echo $restored_text;
?>
