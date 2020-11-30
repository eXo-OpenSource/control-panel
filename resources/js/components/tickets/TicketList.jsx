import React, { Component, useState } from 'react';
import ReactDOM from 'react-dom';
import { Button, Modal, Spinner, Form } from 'react-bootstrap';
import axios from "axios";
import TicketListEntry from "./TicketListEntry";
import {Link} from "react-router-dom";
import {element} from "prop-types";
import Row from "react-bootstrap/Row";
import Col from "react-bootstrap/Col";
import InputGroup from "react-bootstrap/InputGroup";
import Select from "react-select";
import {toast, ToastContainer} from "react-toastify";

export default class TicketList extends Component {
    constructor() {
        super();
        this.state = {
            showCreate: false,
            data: null,
            categories: null,
            categoryOptions: null,
            selectedTicket: null,
            selectedCategories: [],
            state: 'open',
            assignee: 'all',
            search: '',
            page: 0,
            loading: false,
            showSettings: false,
            settings: null,
            settingsTmp: null,
            displaySettings: [{value: 0, label: 'Chat'}, {value: 1, label: 'Thread'}]
        };

        if(Exo.Rank > 0) {
            Echo.private(`tickets`)
                .listen('TicketCreated', this.updateTicket.bind(this))
                .listen('TicketUpdated', this.updateTicket.bind(this));
        } else {
            Echo.private(`tickets.user.${Exo.UserId}`)
                .listen('TicketUpdated', this.updateTicket.bind(this));
        }
    }

    async updateTicket(data) {
        let newData = this.state.data;

        let index = -1;

        newData.items.forEach((element, i) => {
            if(element.Id === data.ticket.Id) {
                index = i;
            }
        })


        if(index !== -1) {
            this.state.data.items[index] = data.ticket;
            this.setState({
                data: newData
            });
        } else {
            newData.items.unshift(data.ticket);
            this.setState({
                data: newData
            });
        }
    }

    async componentDidMount() {
        if(this.state.data === null) {
            await this.loadData();
        }

        if(this.state.categories === null) {
            await this.loadCategories();
        }
    }

    async loadData() {
        if(this.state.loading)
            return;

        await this.setState({
            page: 1,
            loading: true,
            error: null
        });

        try {
            const categories = this.state.selectedCategories.join(',');
            const response = await axios.get('/api/tickets?state=' + this.state.state + '&assignee=' + this.state.assignee + '&categories=' + categories);
            this.setState({
                data: response.data,
                settings: response.data.settings,
                loading: false
            });
        } catch (error) {
            this.setState({
                loading: false,
                error: 'Ein Fehler ist aufgetreten!'
            });
            console.log(error);
        }
    }

    async loadCategories() {
        try {
            const response = await axios.get('/api/tickets/categories');
            let options = [];

            response.data.forEach((category) => {
                options.push({value: category.Id, label: category.Title});
            });

            this.setState({
                categories: response.data,
                categoryOptions: options
            });
        } catch (error) {
            console.log(error);
        }
    }

    async changeState(state) {
        this.setState({
            state: state,
            search: ''
        });
        await this.loadData();
    }

    async onCategoryChange(newValue) {
        let categories = [];

        if(newValue !== null && Array.isArray(newValue)) {
            newValue.forEach((value) => {
                categories.push(value.value);
            });
        }

        this.setState({
            selectedCategories: categories
        });

        await this.loadData();
    }

    async changeAssignee(assignee) {
        this.setState({
            assignee: assignee,
            search: ''
        });
        await this.loadData();
    }

    async onChange(e) {
        if(e.target.type === 'checkbox') {
            this.setState({ [e.target.name]: e.target.checked });
        } else {
            this.setState({ [e.target.name]: e.target.value });
        }
    }

    async search(event) {
        if(event) {
            event.preventDefault();
            event.stopPropagation();
        }

        if(this.state.loading)
            return;

        await this.setState({
            page: 1,
            loading: true,
            error: null
        });

        try {
            const categories = this.state.selectedCategories.join(',');
            const response = await axios.get('/api/tickets?state=' + this.state.state + '&assignee=' + this.state.assignee + '&categories=' + categories + '&search=' + this.state.search);
            this.setState({
                data: response.data,
                settings: response.data.settings,
                loading: false
            });
        } catch(error) {
            this.setState({
                loading: false,
                error: 'Ein Fehler ist aufgetreten!'
            });
            console.log(error);
        }
    }

    async navigatePage(page) {
        if(this.state.loading)
            return;

        await this.setState({
            page: page,
            loading: true,
            error: null
        });

        try {
            const categories = this.state.selectedCategories.join(',');
            const response = await axios.get('/api/tickets?state=' + this.state.state + '&assignee=' + this.state.assignee + '&categories=' + categories + '&search=' + this.state.search + '&page=' + this.state.page);
            this.setState({
                data: response.data,
                settings: response.data.settings,
                loading: false
            });
        } catch(error) {
            this.setState({
                loading: false,
                error: 'Ein Fehler ist aufgetreten!'
            });
            console.log(error);
        }
    }

    async showSettings()
    {
        await this.setState({
            showSettings: true,
        });
    }

    async updateSettings()
    {
        var settings = this.state.settingsTmp;
        if (settings == null) {
            settings = this.state.settings;
        }

        axios.put('/api/tickets/settings', settings).then((response) => {
            this.setState({
                showSettings: false,
                settings: settings,
            });
            toast.success(response.data.Message);
        }).catch((error) => {
            this.setState({
                showSettings: false,
            });

            if(error.response) {
                toast.error(error.response.data.Message);
            } else {
                toast.error('Unbekannter Fehler');
                console.error(error);
            }
        });
    }

    async onChangeSettingDisplay(value)
    {
        var settings = this.state.settingsTmp;
        if (settings == null) {
            settings = this.state.settings;
        }

        settings.display = value.value;


        await this.setState({
            settingsTmp: settings,
        });
    }

    async handleSettingsClose()
    {
        await this.setState({
            showSettings: false,
        });
    }

    render() {
        let navigation = <></>;
        let body = <div className="text-center"><Spinner animation="border"/></div>


        if(this.state.data !== null && this.state.loading === false) {
            if(this.state.data.lastPage > 1) {
                navigation = <nav>
                    <ul className="pagination">
                        <li className={this.state.data.currentPage > 1 ? 'page-item' : 'page-item disabled'} aria-disabled={this.state.data.currentPage > 1 ? 'true' : 'false'}>
                            <button className="page-link" onClick={this.navigatePage.bind(this, this.state.data.currentPage - 1)}>« Zurück</button>
                        </li>
                        <li className={this.state.data.currentPage < this.state.data.lastPage ? 'page-item' : 'page-item disabled'} aria-disabled={this.state.data.currentPage < this.state.data.lastPage ? 'true' : 'false'}>
                            <button className="page-link" onClick={this.navigatePage.bind(this, this.state.data.currentPage + 1)}>Weiter »</button>
                        </li>
                    </ul>
                </nav>;
            }

            body = <div className="row">
                <div className="col-md-12">
                    <div className="card">
                        <div className="card-header">
                            Tickets
                        </div>
                        <div className="card-body">
                            <table className="table table-sm">
                                <thead>
                                <tr>
                                    <th className="d-sm-none">
                                        Benutzer/<br />Kategorie
                                    </th>
                                    <th className="d-none d-sm-table-cell">Benutzer</th>
                                    <th className="d-none d-sm-table-cell">Kategorie</th>
                                    <th className="d-none d-md-table-cell">Titel</th>
                                    <th className="d-sm-none">
                                        Teammitglied/<br />Status
                                    </th>
                                    <th className="d-none d-sm-table-cell">Zugw. Teammitglied</th>
                                    <th className="d-none d-sm-table-cell">Status</th>
                                    <th className="d-none d-md-table-cell">Datum</th>
                                    <th className="d-none d-md-table-cell">Antworten</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                {this.state.data.items.map((ticket, i) => {
                                    if(this.state.state === 'both' ||
                                        (this.state.state === 'open' && ticket.State === 'Open') ||
                                        (this.state.state === 'closed' && ticket.State === 'Closed')) {
                                        if(this.state.assignee === 'all' ||
                                            (this.state.assignee === 'unassigned' && ticket.AssigneeId === null) ||
                                            (this.state.assignee === 'assigned' && ticket.AssigneeId !== null) ||
                                            (this.state.assignee === 'me' && ticket.AssigneeId === Exo.UserId)) {
                                            return <TicketListEntry key={ticket.Id} ticket={ticket} minimal={this.props.minimal} open={this.showEntry}></TicketListEntry>;
                                        }
                                    }
                                })}
                                </tbody>
                            </table>

                            {navigation}
                        </div>
                    </div>
                </div>
            </div>
        }

        return (
            <>
                <ToastContainer />
                <div className="row mb-2">
                    <div className="col-md-12">
                        <div className="btn-group" role="group" aria-label="Basic example">
                            <Button variant="secondary" className={this.state.state === 'open' ? 'active' : ''} onClick={(evt) => this.changeState('open')}>Offen</Button>
                            <Button variant="secondary" className={this.state.state === 'both' ? 'active' : ''} onClick={(evt) => this.changeState('both')}>Offen / Geschlossen</Button>
                            <Button variant="secondary" className={this.state.state === 'closed' ? 'active' : ''} onClick={(evt) => this.changeState('closed')}>Geschlossen</Button>
                        </div>
                        <div className="float-right">
                            <Button variant="secondary" className="mr-2" onClick={this.showSettings.bind(this)}><i className="fas fa-cog"></i></Button>
                            <Link to="/tickets/create" className="btn btn-primary">Ticket erstellen</Link>
                        </div>
                    </div>
                </div>
                {Exo.Rank > 0 ? <div className="row mb-2">
                    <div className="col-md-12">
                        <div className="btn-group" role="group" aria-label="Basic example">
                            <Button variant="secondary" className={this.state.assignee === 'all' ? 'active' : ''} onClick={(evt) => this.changeAssignee('all')}>Alle</Button>
                            <Button variant="secondary" className={this.state.assignee === 'unassigned' ? 'active' : ''} onClick={(evt) => this.changeAssignee('unassigned')}>Nicht zugewiesen</Button>
                            <Button variant="secondary" className={this.state.assignee === 'assigned' ? 'active' : ''} onClick={(evt) => this.changeAssignee('assigned')}>Zugewiesen</Button>
                            <Button variant="secondary" className={this.state.assignee === 'me' ? 'active' : ''} onClick={(evt) => this.changeAssignee('me')}>Mir zugewiesen</Button>
                        </div>
                    </div>
                    <div className="col-md-12 mt-2">
                        <Select
                            className="react-select-container"
                            isMulti
                            classNamePrefix="react-select"
                            options={this.state.categoryOptions}
                            styles={{option: (provided, state) => { return {...provided, backgroundColor: 'transparent'}}}}
                            onChange={this.onCategoryChange.bind(this)}
                        />
                    </div>
                </div> : '' }
                <div className="row mb-4">
                    <div className="col-12 col-sm-8 col-md-6 col-lg-5 col-xl-4">
                        <Form onSubmit={this.search.bind(this)}>
                            <InputGroup>
                                <Form.Control placeholder="Suchebegriff (Benutzer/Titel/Kategorie)" name="search" value={this.state.search} onChange={this.onChange.bind(this)} />
                                <InputGroup.Append>
                                    <Button variant="secondary" onClick={this.search.bind(this)}>Suchen</Button>
                                </InputGroup.Append>
                            </InputGroup>
                        </Form>
                    </div>
                </div>
                {body}


                {this.state.settings != null ?
                <Modal show={this.state.showSettings} onHide={this.handleSettingsClose.bind(this)}>
                    <Modal.Header closeButton>
                        <Modal.Title>Einstellungen</Modal.Title>
                    </Modal.Header>
                    <Modal.Body>
                        <Form>
                            <Form.Group>
                                <Form.Label>Darstellung der Tickets</Form.Label>
                                <Select
                                    className="react-select-container"
                                    classNamePrefix="react-select"
                                    isSearchable={false}
                                    defaultValue={this.state.displaySettings[this.state.settings.display]}
                                    options={this.state.displaySettings}
                                    styles={{option: (provided, state) => { return {...provided, backgroundColor: 'transparent'}}}}
                                    onChange={this.onChangeSettingDisplay.bind(this)}
                                />
                            </Form.Group>
                        </Form>
                    </Modal.Body>
                    <Modal.Footer>
                        <Button variant="primary" onClick={this.updateSettings.bind(this)}>
                            Speichern
                        </Button>
                        <Button variant="secondary" onClick={this.handleSettingsClose.bind(this)}>
                            Schließen
                        </Button>
                    </Modal.Footer>
                </Modal> : ''}
            </>
        );
    }
}

