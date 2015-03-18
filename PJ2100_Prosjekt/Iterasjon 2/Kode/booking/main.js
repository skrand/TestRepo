var prevElement = null;
var selectedClass = " selected";
var childSelectedClass = " timeChildSelected";

function clicked(element)
{
    element.className += selectedClass;
    element.firstElementChild.className += childSelectedClass;

    if (prevElement)
    {
        prevElement.className = prevElement.className.replace(selectedClass, '');
        prevElement.firstElementChild.className = prevElement.firstElementChild.className.replace(childSelectedClass, '');
    }
    prevElement = element;
}


/*
var firstSelected = 100;
var selectedBgColor = '#999';
var unselectedBgColor = '#0fa';
var isSelecting = false;
var curRoomId = -1;
var selection = [];

function clickedTimeElement(element, elementId, isRented, roomId)
{
    // Stop if element is rented
    if (isRented === 1)
    {
        return;
    }

    // Do the selecting/deselecting
    if (isSelecting) // Is selecting
    {
        // End selection
        firstSelected = 100;
        isSelecting = false;
        //curRoomId = -1;

        for (i = 0; i < selection.length; i ++)
        {
            //changeBgColor(selection[i], false);
        }
        //selection = [];
    }
    else // Isn't selecting
    {
        // Start selection
        isSelecting = true;
        firstSelected = elementId;
        curRoomId = roomId;
        changeBgColor(element, true);
        addElementToSelection(element);
    }
}

// Select element (for onhover)
function addHoverToSelection(element, elementId, isRented, roomId)
{
    if (elementId > firstSelected && !isRented && roomId === curRoomId)
    {
        addElementToSelection(element);
        changeBgColor(element, true);
    }
}

// Add element to selection
function addElementToSelection(element)
{
    // Add the element to the selection array
    selection[selection.length] = element;
}

// Change the element background color
function changeBgColor(element, toSelected)
{
    var bgColor = unselectedBgColor;
    if (toSelected)
    {
        bgColor = selectedBgColor;
    }
    element.style.backgroundColor = bgColor;
}

function rentRoom(id)
{
    if (id === curRoomId)
    {
        alert("renting room ");
    }

}
*/