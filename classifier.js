
function toEMailKeys(fsData)
{
    var eMailDict       = Array();

    //Enumerate the data items and restore them with email keys.

    for (var idx = 0; idx < fsData.length; idx++)
    {
        eMailList       = fsData[idx][2].split(' ');

        for (var eIdx = 0; eIdx < eMailList.length; eIdx++)
        {
            if (eMailDict[eMailList[eIdx]] == undefined)
            {
                eMailDict[eMailList[eIdx]]  = [];
            }

            eMailDict[eMailList[eIdx]].push(new Array(fsData[idx][0], fsData[idx][1]));
        }
    }
   
    return eMailDict;
}

function eMailGraphData(eMailDict, label, numEntries)
{
    var graphData       = Array();

    graphData.push(toGraphFormat(eMailDict, label, 5, 'All'));

    return graphData;
}

function toGraphFormat(eMailDict, label, color, flag)
{
    var graphEntry          = Array();

    graphEntry['label']     = label;
    graphEntry['data']      = Array();
    graphEntry['color']     = color;

    if (flag == 'All')
    {
        for (var key in eMailDict)
        {
            for (var idx = 0; idx < eMailDict[key].length; idx++)
            {
                graphEntry['data'].push(eMailDict[key][idx]);
            }
        }
    }
    else
    {
        for (var idx = 0; idx < eMailDict[flag].length; idx++)
        {
            graphEntry['data'].push(eMailDict[flag][idx]);
        }
    }

    return graphEntry;
}

function printDict(dict)
{
    for (var key in dict)
    {
        document.write(key + ' ');
        for (var idx = 0; idx < dict[key].length; idx++)
        {
            document.write(dict[key][idx] + ' ');
        }

        document.write('<br>');
    }
}

function printFSData(fsData)
{
    document.write(fsData.length);
    document.write('<br>');

    for (i=0; i<fsData.length;i++)
    {
        document.write(fsData[i].length);
    }
}
