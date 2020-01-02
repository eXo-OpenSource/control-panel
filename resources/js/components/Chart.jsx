import React, { Component, useState } from 'react';
import ReactDOM from 'react-dom';
import axios from 'axios';
import {Line} from 'react-chartjs-2';
import DatePicker from 'react-datepicker';

import 'bootstrap-daterangepicker/daterangepicker.css';

let data = {
    labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
    datasets: [
        {
            label: 'My First dataset',
            fill: false,
            lineTension: 0.1,
            backgroundColor: 'rgba(75,192,192,0.4)',
            borderColor: 'rgba(75,192,192,1)',
            borderCapStyle: 'butt',
            borderDash: [],
            borderDashOffset: 0.0,
            borderJoinStyle: 'miter',
            pointBorderColor: 'rgba(75,192,192,1)',
            pointBackgroundColor: 'rgba(75,192,192,1)',
            pointBorderWidth: 1,
            pointHoverRadius: 5,
            pointHoverBackgroundColor: 'rgba(75,192,192,1)',
            pointHoverBorderColor: 'rgba(220,220,220,1)',
            pointHoverBorderWidth: 2,
            pointRadius: 1,
            pointHitRadius: 10,
            data: [65, 59, 80, 81, 56, 55, 40]
        },
        {
            label: 'My Second dataset',
            fill: false,
            lineTension: 0.1,
            backgroundColor: 'rgba(136,71,192,0.4)',
            borderColor: 'rgb(167,76,192)',
            borderCapStyle: 'butt',
            borderDash: [],
            borderDashOffset: 0.0,
            borderJoinStyle: 'miter',
            pointBorderColor: 'rgb(167,76,192))',
            pointBackgroundColor: 'rgb(167,76,192)',
            pointBorderWidth: 1,
            pointHoverRadius: 5,
            pointHoverBackgroundColor: 'rgb(167,76,192)',
            pointHoverBorderColor: 'rgba(220,220,220,1)',
            pointHoverBorderWidth: 2,
            pointRadius: 1,
            pointHitRadius: 10,
            data: [43, 23, 54, 45, 76, 23, 1]
        }
    ]
};


export default class Chart extends Component {

    constructor() {
        super();
        this.state = {
            data: data
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
    }
}

var charts = document.getElementsByTagName('react-chart');
for (var index in charts) {
    const component = charts[index];
    const props = Object.assign({}, component.dataset);
    ReactDOM.render(<Chart {...props} />, component);
}
