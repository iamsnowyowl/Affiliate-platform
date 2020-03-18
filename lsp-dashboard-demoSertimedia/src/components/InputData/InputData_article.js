import React, { Component } from 'react'
import {
  AvForm,
  AvGroup,
  AvInput,
} from 'availity-reactstrap-validation';
import { WithContext as ReactTags } from 'react-tag-input';

import {
  Row,
  Col,
  Button
} from 'reactstrap';
import { multiLanguage } from '../Language/getBahasa';
import FileBase64 from 'react-file-base64';
import { path_POST_article, baseUrl } from '../config/config';
import { Digest } from '../../containers/Helpers/digest';
import Axios from 'axios';
import {Editor} from '@tinymce/tinymce-react';

const KeyCodes = {
  comma: 188,
  enter: 13,
};

const delimiters = [KeyCodes.comma, KeyCodes.enter];

export default class InputData_article extends Component {
  constructor(props) {
    super(props);

    this.state = {
      tags: [],
      author:'',
      files:'',
      media:'',
      title:'',
      content:'',
      creator:'',
      categories:''
    };
  }

  handleDeleteTags= (i) => {
    const {tags} = this.state;
    this.setState({
      tags: tags.filter((tag, index) => index !== i)
    })
  }

  handleAddition = (tag) => {
    this.setState(state => ({ tags: [...state.tags, tag]}));
  }

  handleDrag = (tag, currPos, newPos) => {
    const tags = [...this.state.tags];
    const newTags = tags.slice();

    newTags.splice(currPos, 1);
    newTags.splice(newPos, 0, tag);

    this.setState({
      tags: newTags
    });
  }

  handleChange = event => {
    this.setState({
      [event.target.name]: event.target.value
    })
  }

  handleEditorChange=(content) => {
    this.setState({ content });
  }

  handleSubmit =(event,errors,values) => {
    this.setState({errors, values});
    event.preventDefault();

    var formData = new FormData();

    formData.append('tags',this.state.tags[0].text)
    formData.append('author',this.state.author)
    formData.append('media',this.state.files)
    formData.append('title',this.state.title)
    formData.append('content',this.state.content)
    formData.append('creator',this.state.creator)
    formData.append('categories',this.state.categories)

    const auth = Digest(
      path_POST_article,'POST'
    )

    const options = {
      method: auth.method,
      headers: {
        Authorization: auth.digest,
        'X-Lsp-Date': auth.date,
        'Content-Type': 'multipart/form-data'
      },
      url: baseUrl+path_POST_article,
      data: formData
    };

    Axios(options).then(response=>{
    })
  }

  getFiles (files) {
    this.setState({ files: files[0].base64 });
  };
  
  render() {
    const {tags} = this.state;
  
    return (
      <div className="animated fadeIn">
        <AvForm>
          <Row>
            <Col>Media</Col>
            <Col>
            {/* <AvInput
              type="text"
              id="media"
              name="media"
              onChange={this.handleChange}
              placeholder="Link Image"
            />  */}
            <FileBase64 multiple={true} onDone={this.getFiles.bind(this)} />
            </Col>
          </Row>
          <AvGroup row>
            <Col>Author</Col>
            <Col>
            <AvInput
              type="text"
              id="author"
              name="author"
              onChange={this.handleChange}
            />
            </Col>
          </AvGroup>
          <AvGroup row>
            <Col>Creator</Col>
            <Col>
            <AvInput
              type="text"
              id="creator"
              name="creator"
              onChange={this.handleChange}
            />
            </Col>
          </AvGroup>
          <AvGroup row>
            <Col>Categories</Col>
            <Col>
            <AvInput
              type="text"
              id="categories"
              name="categories"
              onChange={this.handleChange}
            />
            </Col>
          </AvGroup>
          <AvGroup row>
            <Col>title</Col>
            <Col>
            <AvInput
              type="text"
              id="title"
              name="title"
              onChange={this.handleChange}
            />
            </Col>
          </AvGroup>
          <AvGroup row>
            <Col>Tags</Col>
            <Col>
              <ReactTags 
                tags={tags}
                handleDelete={this.handleDeleteTags}
                handleAddition={this.handleAddition}
                delimiters={delimiters}
              />
            </Col>
          </AvGroup>
          <AvGroup row>
            <Col>Content</Col>
            <Col>
              <Editor apiKey="vw3w589iihmsopgd343t0n0vwk514w071pr248n55iwgea35" value={this.state.content} onEditorChange={this.handleEditorChange} /> 
            {/* <AvInput
              
              type="textarea"
              id="content"
              name="content"
              onChange={this.handleChange}
            /> */}
            </Col>
          </AvGroup>
          <Button
            className="btn btn-success Btn-Submit"
            color="success"
            size="md"
            type="submit"
            onClick={this.handleSubmit}
          >
            <i className="fa fa-check" /> {multiLanguage.submit}
          </Button>
        </AvForm>
      </div>
    )
  }
}
