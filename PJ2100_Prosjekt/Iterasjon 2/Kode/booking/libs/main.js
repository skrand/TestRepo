var prevElement = null; // Previous clicked element
var selectedClass = " selected"; // Timeblock selected class
var selectedHoverClass = " timeHover"; // Timeblock selected hover class (so the time isnt hovered when selected)
var childSelectedClass = " timeChildSelected"; // Class for the child of the selected element

// Called when clicking a time element
function clicked(element)
{
    // Add classes to the elements
    element.className += selectedClass;
    element.firstElementChild.className += childSelectedClass;
    element.className += selectedHoverClass;
    // If prevElement exists, remove the classes
    if (prevElement)
    {
        removeSelectedClass(prevElement);
    }

    // Assign the previous clicked element to the newPrevElement
    prevElement = element;
}

// Removes the selected classes from the element and its child
function removeSelectedClass(element)
{
    element.className = prevElement.className.replace(selectedClass, '');
    element.className = prevElement.className.replace(selectedHoverClass, '');
    element.firstElementChild.className = prevElement.firstElementChild.className.replace(childSelectedClass, '');
}