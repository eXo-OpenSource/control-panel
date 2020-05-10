import React, { Component, useState } from 'react';
import ReactDOM from 'react-dom';
import {
    BrowserRouter as Router,
    Switch,
    Route,
    Link
} from "react-router-dom";
import TrainingEntry from "./TrainingEntry";
import TrainingList from "./TrainingList";

export default class Trainings extends Component {
    render() {
        return (
            <Router>
                <div>
                    <Switch>
                        <Route path="/trainings/:trainingId" render={(routeProps) => (<TrainingEntry {...routeProps} />)} />
                        <Route path="/trainings" component={TrainingList} />
                    </Switch>
                </div>
            </Router>
        );
    }
}

var practices = document.getElementsByTagName('react-trainings');

for (var index in practices) {
    const component = practices[index];
    if(typeof component === 'object') {
        const props = Object.assign({}, component.dataset);
        ReactDOM.render(<Trainings {...props} />, component);
    }
}

