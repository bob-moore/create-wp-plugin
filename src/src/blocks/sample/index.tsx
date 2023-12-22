import { registerBlockType } from '@wordpress/blocks';
import { __ } from '@wordpress/i18n';
import {
	useBlockProps,
	RichText,
	AlignmentControl,
	BlockControls,
} from '@wordpress/block-editor';

import metadata from './block.json';

type Attributes = {
	align: string;
	content: string;
};

type Props = {
	attributes: Attributes;
	setAttributes: Function;
	clientId: string;
	toggleSelection: Function;
};

function Edit( { attributes, setAttributes }: Props ) {
	const blockProps = useBlockProps();
	const onChangeContent = ( newContent: string ) => {
		setAttributes( { content: newContent } );
	};
	const onChangeAlign = ( newAlign: string ) => {
		setAttributes( {
			align: newAlign === undefined ? 'none' : newAlign,
		} );
	};

	return (
		<div { ...blockProps }>
			<BlockControls>
				<AlignmentControl
					value={ attributes.align }
					onChange={ onChangeAlign }
				/>
			</BlockControls>
			<RichText
				{ ...blockProps }
				tagName="p"
				onChange={ onChangeContent }
				allowedFormats={ [ 'core/bold', 'core/italic' ] }
				value={ attributes.content }
				placeholder={ __( 'Write your text…' ) }
				style={ { textAlign: attributes.align } }
			/>
		</div>
	);
}

function Save( { attributes }: Props ) {
	const blockProps = useBlockProps.save();
	const { content, align } = attributes;
	return (
		<RichText.Content
			{ ...blockProps }
			tagName="p"
			value={ content }
			style={ { textAlign: align } }
		/>
	);
}

registerBlockType( metadata, {
	edit: Edit,
	save: Save,
} );
