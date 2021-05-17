import React, { Component, useState } from 'react';
import {Button, Modal, Spinner, Form, InputGroup} from 'react-bootstrap';
import axios from "axios";
import {Link, withRouter} from "react-router-dom";
import {toast, ToastContainer} from "react-toastify";
import Select from 'react-select';
import AsyncSelect from 'react-select/async';
import queryString from 'query-string';

class TicketCreate extends Component {
    constructor() {
        super();
        this.state = {
            categories: null,
            categoryOptions: null,
            createFor: null,
            createForOption: null,
            closeTicket: false,
            title: '',
            category: '',
            message: '',
            submitting: false,
            fields: {},
        };
    }

    async componentDidMount() {
        let params = queryString.parse(this.props.location.search)
        if(this.state.categories === null) {
            this.loadCategories();
        } else {
            if (params.category) {
                options.forEach((category) => {
                    if (category.value === Number(params.category)) {
                        this.setState({
                            category: category.value
                        });
                    }
                });
            }
        }

        if (params.createFor) {
            try {
                const response = await axios.post('/api/users/search', {
                    id: Number(params.createFor)

                });

                if (response.data && response.data[0] && response.data[0].Id) {
                    this.setState({
                        createFor: response.data[0].Id,
                        createForOption: {value: response.data[0].Id, label: response.data[0].Name}
                    });
                }
            } catch (error) {
                console.log(error);
            }
        }
    }

    async send() {
        if(this.state.submitting)
            return false;

        this.setState({
            submitting: true
        });

        axios.post('/api/tickets', {
            title: this.state.title,
            createFor: this.state.createFor,
            closeTicket: this.state.closeTicket,
            category: this.state.category,
            message: this.state.message,
            fields: this.state.fields,
            submitting: false,
        }).then(() => {
            this.setState({
                submitting: false
            });
            this.props.history.push('/tickets')
        }).catch((error) => {
            this.setState({
                submitting: false
            });

            if(error.response) {
                toast.error(error.response.data.Message);
            } else {
                toast.error('Unbekannter Fehler');
                console.error(error);
            }
        });
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


            let params = queryString.parse(this.props.location.search)

            if (params.category) {
                options.forEach((category) => {
                    if (category.value === Number(params.category)) {
                        this.setState({
                            category: category.value
                        });

                        for(let param in params)
                        {
                            if (param.startsWith('fields_'))
                            {
                                let id = Number(param.replace('fields_', ''));

                                if (!isNaN(id)) {
                                    let fields = this.state.fields;
                                    fields['field' + id] = params[param];

                                    this.setState({
                                        fields: fields
                                    });
                                }
                            }
                        }
                    }
                });
            }

        } catch (error) {
            console.log(error);
        }
    }

    async onChange(e) {
        this.setState({ [e.target.name]: e.target.value });
    }

    async onChangeCategory(value) {
        this.setState({ category: value.value, fields: {} });
    }

    async onChangeField(e) {
        let fields = this.state.fields;
        fields[e.target.name] = e.target.value;

        this.setState({ fields: fields });
    }

    async onChangeSelect(field, newValue) {
        let fields = this.state.fields;

        fields[field] = null;

        if (Array.isArray(newValue)) {
            var entries = [];

            for(var index in newValue)
            {
                entries.push(newValue[index].value);
            }

            fields[field] = entries;
        } else {
            if (newValue && newValue.value) {
                fields[field] = newValue.value;
            }
        }


        this.setState({ fields: fields });
    }

    async onChangeCreateForSelect(newValue) {
        this.setState({
            createFor: newValue.value,
            createForOption: newValue
        });
    }

    async onChangeCloseTicket(e) {
        this.setState({ closeTicket: e.target.checked });
    }

    async loadUsers(inputValue, callback) {
        try {
            const response = await axios.post('/api/users/search', {
                name: inputValue
            });

            let users = [];

            response.data.forEach((entry) => {
               users.push({
                   value: entry.Id,
                   label: entry.Name
               });
            });

            callback(users);
        } catch (error) {
            console.log(error);
        }
    }

    render() {
        if(this.state.categories === null) {
            return <div className="text-center"><Spinner animation="border"/></div>;
        }

        let categoryFields = '';

        if(this.state.category && this.state.category !== '') {
            for(var index in this.state.categories) {
                const category = this.state.categories[index];

                if(parseInt(category.Id) !== parseInt(this.state.category)) {
                    continue;
                }

                if(category && category.fields) {
                    categoryFields = category.fields.map((field, i) => {
                        if (field.Type === 'textarea') {
                            return (
                                <Form.Group key={field.Id}>
                                    <Form.Label>{field.Name}</Form.Label>
                                    <Form.Control name={'field' + field.Id} as="textarea" rows="3" placeholder={field.Name} onChange={this.onChangeField.bind(this)}  />
                                    {field.Description ? <Form.Text className="text-muted" dangerouslySetInnerHTML={{__html: field.Description}}>
                                    </Form.Text> : ''}
                                </Form.Group>
                            );
                        }

                        if (field.Type === 'checkbox') {
                            return (
                                <Form.Group key={field.Id}>
                                    <Form.Check type="checkbox" name={'field' + field.Id} label={field.Name} onChange={this.onChangeField.bind(this)} />
                                    {field.Description ? <Form.Text className="text-muted" dangerouslySetInnerHTML={{__html: field.Description}}>
                                    </Form.Text> : ''}
                                </Form.Group>
                            );
                        }

                        if (field.Type === 'user') {
                            return (
                                <Form.Group key={field.Id}>
                                    <Form.Label>{field.Name}</Form.Label>
                                    <AsyncSelect
                                        className="react-select-container"
                                        classNamePrefix="react-select"
                                        placeholder={field.Name}
                                        name={'field' + field.Id}
                                        loadOptions={this.loadUsers.bind(this)}
                                        styles={{option: (provided, state) => { return {...provided, backgroundColor: 'transparent'}}}}
                                        onChange={this.onChangeSelect.bind(this, 'field' + field.Id)}
                                    />
                                    {field.Description ? <Form.Text className="text-muted" dangerouslySetInnerHTML={{__html: field.Description}}>
                                    </Form.Text> : ''}
                                </Form.Group>
                            );
                        }

                        if (field.Type === 'users' || field.Type === 'admins') {
                            return (
                                <Form.Group key={field.Id}>
                                    <Form.Label>{field.Name}</Form.Label>
                                    <AsyncSelect
                                        closeMenuOnSelect={false}
                                        name={'field' + field.Id}
                                        placeholder={field.Name}
                                        isMulti
                                        cacheOptions
                                        defaultOptions
                                        className="react-select-container"
                                        classNamePrefix="react-select"
                                        loadOptions={this.loadUsers.bind(this)}
                                        styles={{option: (provided, state) => { return {...provided, backgroundColor: 'transparent'}}}}
                                        onChange={this.onChangeSelect.bind(this, 'field' + field.Id)}
                                    />
                                    {field.Description ? <Form.Text className="text-muted" dangerouslySetInnerHTML={{__html: field.Description}}>
                                    </Form.Text> : ''}
                                </Form.Group>
                            );
                        }

                        return (
                            <Form.Group key={field.Id}>
                                <Form.Label>{field.Name}</Form.Label>
                                <Form.Control name={'field' + field.Id} type={field.Type === 'uuid' ? 'text' : field.Type} value={this.state.fields['field' + field.Id]} placeholder={field.Name} onChange={this.onChangeField.bind(this)} />
                                {field.Description ? <Form.Text className="text-muted" dangerouslySetInnerHTML={{__html: field.Description}}>
                                </Form.Text> : ''}
                            </Form.Group>
                        );
                    })
                }
            }
        }

        let category = this.state.category;

        let createFor = null;

        if(Exo.Rank >= 1) {
            createFor = (
                <div>
                    <Form.Group>
                        <Form.Label>Benutzer*</Form.Label>
                        <AsyncSelect
                            className="react-select-container"
                            classNamePrefix="react-select"
                            placeholder='Benutzer'
                            name='createFor'
                            loadOptions={this.loadUsers.bind(this)}
                            value={this.state.createForOption}
                            styles={{option: (provided, state) => { return {...provided, backgroundColor: 'transparent'}}}}
                            onChange={this.onChangeCreateForSelect.bind(this)}
                        />
                        <Form.Text className="text-muted">
                            Ticket für einen Benutzer erstellen
                        </Form.Text>
                    </Form.Group>

                    <Form.Group>
                        <Form.Check type="checkbox" name="closeTicket" label="Ticket direkt schließen" onChange={this.onChangeCloseTicket.bind(this)} />
                    </Form.Group>
                </div>
            );
        }

        return (
            <>
                <ToastContainer />
                <div className="row mb-4">
                    <div className="col-md-12">
                        <Link to="/tickets" className="btn btn-primary float-right">Zurück</Link>
                    </div>
                </div>
                <div className="row">
                    <div className="col-md-12">
                        <div className="card">
                            <div className="card-header">
                                Ticket erstellen
                            </div>
                            <div className="card-body">
                                <Form>
                                    <Form.Group>
                                        <Form.Label>Titel</Form.Label>
                                        <InputGroup>
                                            <Form.Control name="title" type="text" placeholder="Titel" onChange={this.onChange.bind(this)} />
                                        </InputGroup>
                                    </Form.Group>

                                    {createFor}

                                    <Form.Group>
                                        <Form.Label>Kategorie</Form.Label>
                                        <Select
                                            placeholder="Auswählen"
                                            className="react-select-container"
                                            classNamePrefix="react-select"
                                            options={this.state.categoryOptions}
                                            value={this.state.categoryOptions.filter(function(option) {
                                                return option.value === category;
                                            })}
                                            styles={{option: (provided, state) => { return {...provided, backgroundColor: 'transparent'}}}}
                                            onChange={this.onChangeCategory.bind(this)}
                                        />
                                    </Form.Group>

                                    {categoryFields}

                                    <Form.Group>
                                        <Form.Label>Nachricht</Form.Label>
                                        <InputGroup>
                                            <Form.Control as="textarea" rows="3" name="message" placeholder="Nachricht" onChange={this.onChange.bind(this)} />
                                        </InputGroup>
                                    </Form.Group>

                                    <Button disabled={this.state.submitting} onClick={this.send.bind(this)} className="float-right">Erstellen</Button>
                                </Form>
                            </div>
                        </div>
                    </div>
                </div>
            </>
        );
    }
}


export default withRouter(TicketCreate);
