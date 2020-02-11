import React, { Component, useState } from 'react';
import ReactDOM from 'react-dom';
import axios from 'axios';
import {Line} from 'react-chartjs-2';
import DatePicker from 'react-datepicker';
import { Spinner } from 'react-bootstrap';


import 'bootstrap-daterangepicker/daterangepicker.css';

export default class Chart extends Component {

    constructor() {
        super();
        this.state = {
            data: null
        }
    }

    async componentDidMount() {
        const response = await axios.get('/api/charts/' + this.props.chart);

        try {
            this.setState({
                data: response.data
            });
        } catch (error) {
            console.log(error);
        }
    }

    async handleDateChange(e) {
        console.log(e);
    }

    render() {

        const ExampleCustomInput = ({ value, onClick }) => (
            <button className="btn btn-sm btn-primary" onClick={onClick}>
                {value}
            </button>
        );
        if(this.state.data != null) {
            return (
                <div className="card">
                    <div className="card-header">{this.props.title}
                        <div className="float-right">
                            <p>{this.state.data.from} - {this.state.data.to}</p>
                        </div>
                    </div>

                    <div className="card-body">
                        <Line data={this.state.data.chart} />
                    </div>
                </div>
            );
        } else {

            return (
                <div className="card">
                    <div className="card-header">{this.props.title}
                    </div>

                    <div className="card-body">
                        <div className="text-center">
                            <Spinner animation="border" />
                        </div>
                    </div>
                </div>
            );
        }
    }
}

var charts = document.getElementsByTagName('react-chart');
for (var index in charts) {
    const component = charts[index];
    if(typeof component === 'object') {
        const props = Object.assign({}, component.dataset);
        ReactDOM.render(<Chart {...props} />, component);
    }
}

