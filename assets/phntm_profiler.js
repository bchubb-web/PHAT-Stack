const onDOMContentLoaded = (init) => {
  if (['complete', 'interactive', 'loaded'].includes(document.readyState)) {
    init();
  } else {
    document.addEventListener('DOMContentLoaded', init);
  }
};
const findComments = function(el) {
    var arr = [];
    for(var i = 0; i < el.childNodes.length; i++) {
        var node = el.childNodes[i];
        if(node.nodeType === 8) {
            arr.push(node);
        } else {
            arr.push.apply(arr, findComments(node));
        }
    }
    return arr;
};

const getEntries = async () => {
    entries = await fetch('/vendor/bchubbweb/phntm/oneoff/phntm_profiler.php');
    return entries;
};

const generateProfilerTable = (data) => {

};

onDOMContentLoaded(() => {
    findComments(document).forEach((comment) => {
        if (comment.textContent === ' profiler-insert ') {
            getEntries().then((entries) => {
                entries.json().then((data) => {
                    console.log(data);
                    // add dialog element at the end of the body
                    const dialog = document.createElement('dialog');
                    dialog.style.minWidth = '60vw';
                    dialog.open = true;
                    dialog.id = 'profiler';
                        //<dialog open style="min-width: 60vw" id="profiler"><table style="width: 100%"><tbody>
                    dialog.innerHTML = `
                        <table class="profiler-table">
                            <thead>
                                <tr>
                                    <th>Time</th>
                                    <th>Memory</th>
                                    <th>Query Count</th>
                                    <th>Queries</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>0.0000</td>
                                    <td>0</td>
                                    <td>0</td>
                                    <td></td>
                                </tr>
                            </tbody>
                        </table>
                    `;
                    document.body.appendChild(dialog);
                });
            });
        }
    });
});
