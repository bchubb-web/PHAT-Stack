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
    entries = await fetch('/api/profiler');
    return entries;
};

const generateProfilerTable = (data) => {
    const dialog = document.createElement('dialog');
    dialog.style.minWidth = '60vw';
    dialog.open = true;
    dialog.id = 'profiler';

    tableString = '<table style="width: 100%"><tbody>';
    for (let i = 0; i < data.length -1; i++) {
        const entry = data[i];
        const duration = data[i+1] ? data[i+1]['timestamp'] - entry['timestamp'] : 0;
        tableString += '<tr><td>' + entry['parent'] + '</td><td>' + entry['message'] + '</td><td>' + duration.toPrecision(8) + '</td></tr>';
    }
    tableString += '<tr><td>' + data[data.length -1]['parent'] + '</td><td>' + data[data.length -1]['message'] + '</td><td>n/a</td></tr></tbody></table>';

    dialog.innerHTML = tableString;
    return dialog;
};

onDOMContentLoaded(() => {
    findComments(document).forEach((comment) => {
        if (comment.textContent === ' profiler-insert ') {
            getEntries().then((entries) => {
                entries.json().then((data) => {
                    const dialog = generateProfilerTable(data);

                    document.body.appendChild(dialog);
                });
            });
        }
    });
});
