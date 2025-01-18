import React, {useState} from 'react';
import {CartItems} from "@/types";
import {Link, router, useForm} from "@inertiajs/react";
import {productRoute} from "@/helpers";
import TextInput from "@/Components/core/TextInput";
import CurrencyFormatter from "@/Components/core/CurrencyFormatter";

function CartItem(
  {item}: {item: CartItems}
) {

  const deleteForm = useForm({
    option_ids: item.option_ids
  })
  // console.log(deleteForm);

  const [error, setError] = useState('');
// console.log(item.product_id)
  const onDeleteClick = ()=>{
    deleteForm.delete(route('cart.destroy', item.product_id),  {
      preserveScroll: true,
    })
  }

  const handleQuantityChange = (env:React.ChangeEvent<HTMLSelectElement>)=>{
    setError('');
    // console.log(item.product_id);
    router.put(route('cart.update', item.product_id),{
      quantity: env.target.value,
      option_ids: item.option_ids,
      product_id: item.product_id
    }, {
      preserveScroll: true,
      onError: (error)=>{
        setError(Object.values(error)[0])
      }
    })
  };


  return (
    <>
      <div key={item.id} className='flex gap-6 p-3'>
        <Link href={productRoute(item)} className='w-32 min-w-32 min-h-32 flex justify-center self-start'>
          <img src={item.image} alt="" className='max-w-full max-h-full'/>
        </Link>
        <div className="flex-1 flex flex-col">
          <div className="flex-1">
            <h3 className="mb-3 text-lg font-semibold">
              <Link href={productRoute(item)}>
                {item.title}
              </Link>
            </h3>
            <div className="text-sm">
              {item.options.map(option =>(
                <div key={option.id}>
                  <strong className='font-bold'>{option.type.name}: </strong>
                  {option.name}
                </div>
              ))}
            </div>
            <div className="text-sm">
              <strong className='font-bold'>Price: </strong>
              <CurrencyFormatter amount={item.price}/>
            </div>
          </div>
          <div className="flex justify-between items-center mt-4">
            <div className="flex gap-4 items-center">
              <div className="text-sm">Quantity: </div>
              <div className={error ? "tooltip tooltip-open tooltip-error": ""} data-tip={error}>
                <TextInput type='number'
                           defaultValue={item.quantity}
                           onBlur={handleQuantityChange}
                           className='input-sm w-16'>
                </TextInput>
                <button onClick={()=> onDeleteClick()} className="btn btn-sm btn-ghost">Delete</button>
                <button className="btn btn-sm btn-ghost">Save for Later</button>
              </div>
                <div className="font-bold text-lg">
                  <CurrencyFormatter amount={item.price * item.quantity}/>
                </div>
            </div>
          </div>
        </div>
      </div>
      <div className="divider"></div>
    </>
  );
}

export default CartItem;
