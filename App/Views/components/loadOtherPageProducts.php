<button class="btn btn_arrow" id="btn_previous-page" onclick="switchPage(-1)">
    <i class="fa-solid fa-chevron-left fa-sm"></i>
</button>
<a href="#productList" id="first-page" onclick="switchPage('first')" hidden>1</a>
<p id="pages-before" hidden>...</p>
<a href="#productList" id="previous-page" onclick="switchPage(-1)" hidden></a>
<a href="#productList" id="current-page">1</a>
<a href="#productList" id="next-page" onclick="switchPage(1)">2</a>
<p id="pages-after">...</p>
<a href="#productList" id="last-page" onclick="switchPage('last')"></a>
<button class="btn btn_arrow" id="btn_next-page" onclick="switchPage(1)">
    <i class="fa-solid fa-chevron-right fa-sm"></i>
</button>
