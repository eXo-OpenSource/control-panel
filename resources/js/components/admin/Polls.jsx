import React, { Component, useState } from 'react';
import ReactDOM from 'react-dom';
import TicketListEntry from "../tickets/TicketListEntry";
import { Beforeunload } from 'react-beforeunload';
import { Pie, Doughnut } from "react-chartjs-2";
import Button from "react-bootstrap/Button";
import axios from "axios";
import {Form, InputGroup, Modal, Spinner} from "react-bootstrap";
import classNames from 'classnames'

export default class AdminPolls extends Component {
    constructor() {
        super();
        this.state = {
            users: [],
            title: '',
            url: '',
            agree: 0,
            disagree: 0,
            data: null,
            loading: true,
            submitting: false,
            chartData: {},
            chartOptions: {'maintainAspectRatio': false}
        };

        /*
        this.state.data = {
            'datasets': [
                {
                    'data': [6, 4, 5],
                    'backgroundColor': ['rgba(69, 161, 100, 1)', 'rgba(170, 170, 170, 1)', 'rgba(209, 103, 103, 1)'],
                    'borderWidth': 0
                }
            ],
            'labels': ['Dafür', 'Enthalten', 'Dagegen']
        };
        this.state.options = {

            'legend': {
                'display': true,
                'labels': {
                    'fontColor': 'rgba(255, 255, 255, 1)'
                }
            }
        };
        */

        Echo.private(`admin.polls`)
            .listen('Admin\\PollUpdate', this.pollUpdate.bind(this));

        Echo.join(`admin.polls`)
            .here((users) => {
                this.setState({users: users});
            })
            .joining((user) => {
                console.log('Joining', user);
                const users = this.state.users;

                let i = users.length
                while (i--) {
                    if (users[i].id === user.id) {
                        users.splice(i, 1);
                    }
                }

                users.push(user);
                this.setState({users: users});
            })
            .leaving((user) => {
                console.log('Leaving', user);
                const users = this.state.users;

                let i = users.length
                while (i--) {
                    if (users[i].id === user.id) {
                        users.splice(i, 1);
                    }
                }

                this.setState({users: users});
            });

    }

    async componentDidMount() {
        this.loadData();
    }

    async loadData()
    {
        try {
            const response = await axios.get('/api/admin/polls');
            this.setState({
                data: response.data === '' ? null : response.data,
                loading: false
            });
            this.calculateChartData();
        } catch(error) {
            this.setState({
                loading: false
            });
        }
    }

    async pollUpdate(data) {
        console.log(data);

        this.setState({
            data: data.poll
        });
        this.calculateChartData();
    }

    async calculateChartData()
    {
        if(this.state.data === null)
            return;

        let agree = 0;
        let disagree = 0;

        this.state.data.votes.forEach((vote) => {
           if(vote.Vote === 0) {
               disagree++;
           } else {
               agree++;
           }
        });

        this.setState({
           chartData: {
               'datasets': [
                   {
                       'data': [agree, disagree],
                       'backgroundColor': ['rgba(69, 161, 100, 1)', 'rgba(209, 103, 103, 1)'],
                       'borderWidth': 0
                   }
               ],
               'labels': ['Dafür', 'Dagegen']
           },
            agree: agree,
            disagree: disagree
        });
    }

    async voteAgree()
    {
        this.setState({
            submitting: true
        });

        try {
            await axios.post('/api/admin/polls', {
                'action': 'vote',
                'vote': 'agree'
            });
            this.setState({
                submitting: false
            });
        } catch(error) {
            this.setState({
                submitting: false
            });
            console.log(error);
        }
    }

    async voteDisagree()
    {
        this.setState({
            submitting: true
        });

        try {
            await axios.post('/api/admin/polls', {
                'action': 'vote',
                'vote': 'disagree'
            });
            this.setState({
                submitting: false
            });
        } catch(error) {
            this.setState({
                submitting: false
            });
            console.log(error);
        }
    }

    async finish()
    {
        this.setState({
            submitting: true
        });

        try {
            await axios.post('/api/admin/polls', {
                'action': 'finish'
            });
            this.setState({
                submitting: false
            });
        } catch(error) {
            this.setState({
                submitting: false
            });
            console.log(error);
        }
    }

    async onChange(e)
    {
        this.setState({ [e.target.name]: e.target.value });
    }

    async create()
    {
        this.setState({
            submitting: true
        });

        try {
            await axios.post('/api/admin/polls', {
                'action': 'create',
                'title': this.state.title,
                'url': this.state.url
            });
            this.setState({
                submitting: false,
                title: '',
                url: ''
            });
        } catch(error) {
            this.setState({
                submitting: false
            });
            console.log(error);
        }
    }

    render() {
        if(this.state.loading === true) {
            return <div className="text-center"><Spinner animation="border"/></div>;
        }

        let users = '';

        this.state.users.map((user, i) => {
            users += (users === '' ? '' : ', ') + user.name;
        });

        const activeUsers = <div className="row">
            <div className="col-12 mt-4">
                <h3>Aktive Benutzer</h3>
                <span className="d-block">{users}</span>
            </div>
        </div>;

        if(this.state.data === null)
        {
            return <div className="col-12">
                <div className="row">
                    <div className="col-6">
                        <Form>
                            <Form.Group>
                                <Form.Label>Thema</Form.Label>
                                <InputGroup>
                                    <Form.Control name="title" type="text" placeholder="Thema" value={this.state.title} onChange={this.onChange.bind(this)} />
                                </InputGroup>
                            </Form.Group>

                            <Form.Group>
                                <Form.Label>URL</Form.Label>
                                <InputGroup>
                                    <Form.Control name="url" type="text" placeholder="URL" value={this.state.url} onChange={this.onChange.bind(this)} />
                                </InputGroup>
                                <Form.Text className="text-muted">
                                    Optional
                                </Form.Text>
                            </Form.Group>

                            <Form.Group>
                                <Button disabled={this.state.submitting} variant="primary" onClick={this.create.bind(this)}>
                                    Erstellen
                                </Button>
                            </Form.Group>
                        </Form>
                    </div>
                    <div className="col-6">
                        {activeUsers}
                    </div>
                </div>
            </div>;
        }

        return (
            <div className="col-12">
                <div className="row">
                    <div className="col-6">
                        <h2>{this.state.data.Title}</h2>
                        {this.state.data.URL ? <p><a href={this.state.data.URL}>{this.state.data.URL}</a></p> : ''}

                        <div style={{'height': '40vh', 'width': '40vh'}}>
                            <Pie data={this.state.chartData} options={this.state.chartOptions} />
                        </div>
                        <div className="mt-4">
                            <Button disabled={this.state.submitting} variant={'success'}  onClick={this.voteAgree.bind(this)}>Dafür</Button>
                            <Button disabled={this.state.submitting} variant={'danger'} onClick={this.voteDisagree.bind(this)} className="ml-1">Dagegen</Button>
                        </div>
                    </div>
                    <div className="col-6">
                        <div className="row">
                            <div className="col-12">
                                <h3>Ergebnis</h3>
                                <span className="d-block">Dafür: {this.state.agree}</span>
                                <span className="d-block">Dagegen: {this.state.disagree}</span>
                                <span className="d-block">Gesamt: {this.state.agree + this.state.disagree}</span>
                                <button className="btn btn-secondary btn-sm mt-2" onClick={this.finish.bind(this)}>Abschließen</button>
                            </div>
                        </div>
                        <div className="row">
                            <div className="col-12 mt-4">
                                <h3>Stimmen</h3>
                                {this.state.data.votes.map((vote) => {
                                    return <span className="d-block" key={vote.AdminId}>
                                        {vote.Admin} <span className={classNames('badge', vote.Vote === 1 ? 'badge-success' : 'badge-danger')}>{vote.Vote === 1 ? 'Dafür' : 'Dagegen'}</span>
                                    </span>;
                                })}
                            </div>
                        </div>
                        {activeUsers}
                    </div>
                </div>
            </div>
        );
    }
}

var polls = document.getElementsByTagName('react-admin-polls');

for (var index in polls) {
    const component = polls[index];
    if(typeof component === 'object') {
        const props = Object.assign({}, component.dataset);
        ReactDOM.render(<AdminPolls {...props} />, component);
    }
}

