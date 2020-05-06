function groupEventsById(events) {
    var eventsById = {};
    var i, event;

    for (i = 0; i < events.length; i++) {
        event = events[i];
        (eventsById[event._id] || (eventsById[event._id] = [])).push(event);
    }
    return eventsById;
}

function isInverseBgEvent(event) {
    return getEventRendering(event) === 'inverse-background';
}
// Computes the intersection of the two ranges. Returns undefined if no intersection.
// Expects all dates to be normalized to the same timezone beforehand.
// TODO: move to date section?
function intersectRanges(subjectRange, constraintRange) {
    var subjectStart = subjectRange.start;
    var subjectEnd = subjectRange.end;
    var constraintStart = constraintRange.start;
    var constraintEnd = constraintRange.end;
    var segStart, segEnd;
    var isStart, isEnd;

    if (subjectEnd > constraintStart && subjectStart < constraintEnd) { // in bounds at all?

        if (subjectStart >= constraintStart) {
            segStart = subjectStart.clone();
            isStart = true;
        }
        else {
            segStart = constraintStart.clone();
            isStart =  false;
        }

        if (subjectEnd <= constraintEnd) {
            segEnd = subjectEnd.clone();
            isEnd = true;
        }
        else {
            segEnd = constraintEnd.clone();
            isEnd = false;
        }

        return {
            start: segStart,
            end: segEnd,
            isStart: isStart,
            isEnd: isEnd
        };
    }
}