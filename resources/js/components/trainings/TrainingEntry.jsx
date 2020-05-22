import React, { Component, useState } from 'react';
import ReactDOM from 'react-dom';
import {Button, Modal, Spinner, Form, InputGroup, OverlayTrigger, Tooltip, Row, Col} from 'react-bootstrap';
import axios from "axios";

import {
    Link,
    useParams
} from "react-router-dom";
import ConfirmDialog from './../ConfirmDialog';
import SelectUserDialog from './../SelectUserDialog';
import SelectUserFromListDialog from "../SelectUserFromListDialog";

export default class TrainingEntry extends Component {
    constructor({match}) {
        super();
        this.state = {
            data: null,
            notes: '',
            notesModified: false,
            trainingId: match.params.trainingId,
            showAddUserDialog: false,
            showRemoveUserDialog: false,
            showEditNotes: false,
            contentNotes: '',
            contentId: null,
        };
    }
    async componentDidMount() {
        if(this.state.data === null) {
            this.loadData();
        }
    }

    async toggleAddUserDialog() {
        this.setState({showAddUserDialog: !this.state.showAddUserDialog});
    }

    async toggleRemoveUserDialog(userId) {
        this.setState({showRemoveUserDialog: !this.state.showRemoveUserDialog, removeUserId: userId});
    }

    async onChange(e) {
        this.setState({
            [e.target.name]: e.target.value,
            [e.target.name + 'Modified']: true,
        });
    }

    async send() {
        try {
            const response = await axios.put('/api/trainings/' + this.state.ticketId, {
                type: 'addMessage',
                message: this.state.message,
            });

            this.setState({message: ''});
            this.loadData();
        } catch(error) {
            console.log(error);
        }
    }

    async finish() {
        try {
            const response = await axios.put('/api/trainings/' + this.state.trainingId, {
                type: 'finish',
            });

            this.setData(response);
        } catch(error) {
            console.log(error);
        }
    }

    async handleEditNotesClose()
    {
        this.setState({
            showEditNotes: false,
        })
    }

    async updateContentNotes()
    {
        try {
            const response = await axios.put('/api/trainings/' + this.state.trainingId, {
                type: 'updateContentNotes',
                contentId: this.state.contentId,
                contentNotes: this.state.contentNotes
            });
            this.handleEditNotesClose();
            this.setData(response);
        } catch(error) {
            console.log(error);
        }
    }

    async editContentNote(contentId, notes)
    {
        this.setState({
            contentId: contentId,
            contentNotes: notes,
            showEditNotes: true,
        })
    }

    async loadData() {
        try {
            const response = await axios.get('/api/trainings/' + this.state.trainingId);

            this.setData(response);
        } catch (error) {
            console.log(error);
        }
    }

    async setData(response) {
        this.setState({
            data: response.data,
        });

        if(!this.state.notesModified) {
            this.setState({
                notes: response.data.Notes,
            });
        }
    }

    async toggleRole(userId) {
        try {
            const response = await axios.put('/api/trainings/' + this.state.trainingId, {
                type: 'toggleRole',
                userId: userId
            });

            this.setData(response);
        } catch(error) {
            console.log(error);
        }
    }

    async toggleState(id) {
        try {
            const response = await axios.put('/api/trainings/' + this.state.trainingId, {
                type: 'toggleState',
                contentId: id
            });

            this.setData(response);
        } catch(error) {
            console.log(error);
        }
    }

    async toggleAllState() {
        try {
            const response = await axios.put('/api/trainings/' + this.state.trainingId, {
                type: 'toggleAllState'
            });

            this.setData(response);
        } catch(error) {
            console.log(error);
        }
    }

    async updateNotes() {
        try {
            const response = await axios.put('/api/trainings/' + this.state.trainingId, {
                type: 'updateNotes',
                notes: this.state.notes
            });

            this.setState({
                notesModified: false,
            });
            this.setData(response);
        } catch(error) {
            console.log(error);
        }
    }

    async addUser(userIds) {
        try {
            const response = await axios.put('/api/trainings/' + this.state.trainingId, {
                type: 'addUser',
                userIds: userIds
            });

            this.setData(response);
        } catch(error) {
            console.log(error);
        }
    }
    async hideUserDialog() {
        this.setState({showAddUserDialog: false});
    }

    async removeUser() {
        try {
            const response = await axios.put('/api/trainings/' + this.state.trainingId, {
                type: 'removeUser',
                userId: this.state.removeUserId
            });

            this.setData(response);
        } catch(error) {
            console.log(error.response);
        }
    }

    render() {
        if(this.state.data === null) {
            return <div className="text-center"><Spinner animation="border"/></div>;
        }

        return (
            <>
                <div className="row mb-4">
                    <div className="col-md-12 d-flex justify-content-between align-items-start">
                        <div>
                            <p className="h3">{this.state.data.Name}</p>
                        </div>
                        <div>
                            {
                                this.state.data.State === 0 ? <Button className="btn btn-success mr-2" onClick={this.finish.bind(this)}>Abschließen</Button> : null
                            }
                            <Link to="/trainings" className="btn btn-primary">Zurück</Link>
                        </div>
                    </div>
                </div>
                <div className="row">
                    <div className="col-md-12">
                        <div className="row">
                            <div className="col-md-8">
                                {this.state.data.contents.map((content, i) => {
                                    return (
                                        <div key={content.Id} className="card">
                                            <div className="card-header">
                                                <div className="d-flex justify-content-between align-items-start">
                                                    <div>
                                                        <input type="checkbox"
                                                               disabled={this.state.data.State === 1}
                                                               onChange={this.toggleState.bind(this, content.Id)}
                                                               checked={content.State} /> <span style={content.State ? {'textDecoration': 'line-through'} : {}}>{content.Name}</span>
                                                    </div>
                                                    {/*<div style={{height: '21px'}}>
                                                        {this.state.data.State !== 1 ? (
                                                            <Button onClick={this.editContentNote.bind(this, content.Id, content.Notes)}
                                                                    variant="link" style={{color: 'white', padding: 0}} size="sm">
                                                                <i className="fas fa-pencil-alt" style={{margin: 0}}></i>
                                                            </Button>
                                                        ) : null}
                                                    </div>*/}
                                                </div>
                                            </div>
                                            {
                                                (content.Description || content.Notes) ? (<div className="card-body" style={content.State ? {'textDecoration': 'line-through'} : {}}>
                                                    {content.Description ? content.Description : null}
                                                    {content.Notes ? <><h5 className={content.Description ? 'mt-4' : null}>Notizen</h5>{content.Notes}</> : null}
                                                </div>) : (<></>)
                                            }
                                        </div>
                                    );
                                })}
                            </div>
                            <div className="col-md-4">
                                <div className="card">
                                    <div className="card-header">
                                        Details
                                    </div>
                                    <div className="card-body">
                                        <table className="table table-sm">
                                            <tbody>
                                            <tr>
                                                <td>Teilnehmer</td>
                                                <td>
                                                    {this.state.data.users.map((user, i) => {
                                                        return (
                                                            <div key={user.UserId}>
                                                                <Row>
                                                                    <Col xs='1'>
                                                                        {
                                                                            user.Role == 1 ? (
                                                                                <OverlayTrigger
                                                                                    placement="top"
                                                                                    overlay={
                                                                                        <Tooltip id='tooltip-info'>
                                                                                            Ausbilder
                                                                                        </Tooltip>
                                                                                    }
                                                                                    >
                                                                                    <Button variant="link" style={{color: "white"}} size="sm">
                                                                                    <i className="fas fa-chalkboard-teacher"></i>
                                                                                    </Button>
                                                                                    </OverlayTrigger>
                                                                            ) : (
                                                                                <OverlayTrigger
                                                                                    placement="top"
                                                                                    overlay={
                                                                                        <Tooltip id='tooltip-info'>
                                                                                            Teilnehmer
                                                                                        </Tooltip>
                                                                                    }
                                                                                >
                                                                                    <Button variant="link" style={{color: "white"}} size="sm">
                                                                                        <i className="fas fa-user"></i>
                                                                                    </Button>
                                                                                </OverlayTrigger>
                                                                            )
                                                                        }
                                                                    </Col>
                                                                    <Col xs='6'>
                                                                        <a href={'/users/' + user.UserId}>{user.User}</a>
                                                                    </Col>
                                                                    <Col xs='4'>
                                                                        {(user.UserId !== this.state.data.UserId && this.state.data.State !== 1) ? (<>
                                                                                <Button onClick={this.toggleRole.bind(this, user.UserId)} variant="link" style={{color: 'white', padding: 0, paddingRight: '8px'}} size="sm"><i className="fas fa-pencil-alt"></i></Button>
                                                                                <Button onClick={this.toggleRemoveUserDialog.bind(this, user.UserId)} variant="link" style={{color: '#d16767', padding: 0}} size="sm"><i className="fas fa-times-circle"></i></Button>
                                                                            </>)
                                                                            :null}
                                                                    </Col>
                                                                </Row>
                                                            </div>
                                                        );
                                                    })}
                                                    {this.state.data.State !== 1 ?
                                                        (<Button onClick={this.toggleAddUserDialog.bind(this)}
                                                                 size="sm"
                                                                 variant="secondary">Benutzer hinzufügen</Button>) :
                                                        null
                                                    }
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Status</td>
                                                <td>{this.state.data.StateText}</td>
                                            </tr>
                                            <tr>
                                                <td>Notizen</td>
                                                <td>
                                                    <Form.Control as="textarea" rows="3" name="notes"
                                                                  onChange={this.onChange.bind(this)}
                                                                  disabled={this.state.data.State === 1}
                                                                  value={this.state.notes} />
                                                    {this.state.data.State !== 1 ? (
                                                        <Button onClick={this.updateNotes.bind(this)}
                                                                size="sm" variant="primary"
                                                                disabled={!this.state.notesModified}
                                                                className="mt-2 float-right">Speichern</Button>
                                                    ) : null}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td></td>
                                                <td><Button onClick={this.toggleAllState.bind(this)}
                                                            size="sm" variant="primary"
                                                            disabled={this.state.data.State === 1}
                                                            className="mt-2 float-right">Alle Inhalte abschließen</Button></td>
                                            </tr>
                                            </tbody>
                                        </table>

                                        <SelectUserFromListDialog show={this.state.showAddUserDialog} type={this.state.data.ElementType === 2 ? 'faction' : this.state.data.ElementType === 3 ? 'company' : ''} id={this.state.data.ElementId} multiple={true} buttonText="hinzufügen" onClosed={this.hideUserDialog.bind(this)} onSelectUser={this.addUser.bind(this)} />
                                        <ConfirmDialog
                                            show={this.state.showRemoveUserDialog}
                                            buttonText="entfernen"
                                            buttonVariant="danger"
                                            title="Benutzer entfernen"
                                            text="Möchtest du den Benutzer entfernen?"
                                            onConfirm={this.removeUser.bind(this)}
                                        />

                                        <Modal show={this.state.showEditNotes}>
                                            <Modal.Header closeButton>
                                                <Modal.Title>Notizen bearbeiten</Modal.Title>
                                            </Modal.Header>
                                            <Modal.Body>
                                                <Form.Control as="textarea" rows="3" name="contentNotes" onChange={this.onChange.bind(this)} value={this.state.contentNotes} />
                                            </Modal.Body>
                                            <Modal.Footer>
                                                <Button variant="primary" onClick={this.updateContentNotes.bind(this)}>
                                                    Speichern
                                                </Button>
                                                <Button variant="secondary" onClick={this.handleEditNotesClose.bind(this)}>
                                                    Schließen
                                                </Button>
                                            </Modal.Footer>
                                        </Modal>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </>
        );
    }
}
