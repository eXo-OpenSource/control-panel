import React, { Component, useState } from 'react';
import ReactDOM from 'react-dom';
import {
    BrowserRouter as Router,
    Switch,
    Route,
    Link
} from "react-router-dom";
import TicketCreate from "./TicketCreate";
import TicketEntry from "./TicketEntry";
import TicketList from "./TicketList";

export default class Tickets extends Component {
    render() {
        return (
            <Router>
                <div>
                    <Switch>
                        <Route path="/tickets/create" component={TicketCreate} />
                        <Route path="/tickets/:ticketId" render={(routeProps) => (<TicketEntry {...routeProps} minimal={this.props.minimal}/>)} />
                        <Route path="/tickets" component={TicketList} />
                    </Switch>
                </div>
            </Router>
        );
    }
}

var tickets = document.getElementsByTagName('react-tickets');

for (var index in tickets) {
    const component = tickets[index];
    if(typeof component === 'object') {
        const props = Object.assign({}, component.dataset);
        ReactDOM.render(<Tickets {...props} />, component);
    }
}

