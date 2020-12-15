import React, { Component } from 'react';
import ReactDOM from 'react-dom';
import {BrowserRouter as Router, Route, Switch} from "react-router-dom";
import PollActive from "./PollActive";
import PollList from "./PollList";
import PollEntry from "./PollEntry";

export default class Polls extends Component {
    render() {
        return (
            <Router>
                <div>
                    <Switch>
                        <Route path="/admin/polls/history" component={PollList} />
                        <Route path="/admin/polls/:pollId" render={(routeProps) => (<PollEntry {...routeProps} />)} />
                        <Route path="/admin/polls" component={PollActive} />
                    </Switch>
                </div>
            </Router>
        );
    }
}

var polls = document.getElementsByTagName('react-admin-polls');

for (var index in polls) {
    const component = polls[index];
    if(typeof component === 'object') {
        const props = Object.assign({}, component.dataset);
        ReactDOM.render(<Polls {...props} />, component);
    }
}

