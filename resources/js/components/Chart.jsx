import React, { Component, useState } from 'react';
import ReactDOM from 'react-dom';
import axios from 'axios';
import { Line, Doughnut } from 'react-chartjs-2';
import { Spinner } from 'react-bootstrap';


import 'bootstrap-daterangepicker/daterangepicker.css';

export default class Chart extends Component {

    constructor() {
        super();
        this.state = {
            data: null,
            status: null
        };
    }

    async componentDidMount() {

        try {
            const response = await axios.get('/api/charts/' + this.props.chart);

            if(response.data && response.data.tooltips) {
                if (!response.data.options) {
                    response.data.options = {};
                }
                if (!response.data.options.tooltips) {
                    response.data.options.tooltips = {};
                }
                response.data.options.tooltips.callbacks = {};
                response.data.options.tooltips.callbacks.label = this.handleLabel.bind(this);
            }

            this.setState({
                data: response.data,
                status: response.data.status
            });
        } catch (error) {
            this.setState({
                status: 'Access Denied'
            })
        }
    }

    handleLabel(tooltipItem, data) {
        var label = data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index] || '';

        if(this.state.data.tooltips === 'money') {
            return '$ ' + Number(label).toLocaleString('de-AT');
        }

        return label;
    }

    async handleDateChange(e) {
        console.log(e);
    }

    render() {
        if(this.state.status) {
            if(this.state.status !== 'Success') {
                let message = <p>Der Zugriff auf die Daten wurden verweigert!</p>;

                if(this.state.status === 'Error') {
                    message = <p>Die Daten konnten nicht geladen werden!</p>;
                }

                return (
                    <div className="card">
                        <div className="card-header">{this.props.title}
                        </div>

                        <div className="card-body">
                            <div className="text-center">
                                {message}
                            </div>
                        </div>
                    </div>
                );
            } else {
                let chart = <Line data={this.state.data.data} options={this.state.data.options} />;

                if(this.state.data.type === 'doughnut') {
                    chart = <Doughnut data={this.state.data.data} options={this.state.data.options} />;
                }

                return (
                    <div className="card">
                        <div className="card-header">{this.props.title}
                            <div className="float-right">
                                <span>{this.state.data.from} - {this.state.data.to}</span>
                            </div>
                        </div>

                        <div className="card-body"  style={{'height': '40vh'}}>
                            {chart}
                        </div>
                    </div>
                );
            }
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

