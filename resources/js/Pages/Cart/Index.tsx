import React from 'react';
import {GroupedCartItems, PageProps} from "@/types";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import {Head, Link} from "@inertiajs/react";
import CurrencyFormatter from "@/Components/core/CurrencyFormatter";
import PrimaryButton from "@/Components/core/PrimaryButton";
import {CreditCardIcon} from "@heroicons/react/24/outline";
import index from "@/Pages/Cart/Index";
import CartItem from "@/Components/app/CartItem";

function Index(
  {
    csrf_token,
    totalQuantity,
    totalPrice,
    cartItems
  }: PageProps<{cartItems: Record<any, GroupedCartItems>}>
) {

  return (
    <AuthenticatedLayout>
      <Head title={'Your Carts'}/>

      <div className="container mx-auto p-8 flex flex-col lg:flex-row gap-4">
        <div className="card flex1 bg-white dark:bg-gray-800 order-2 lg:order-1">
          <div className="card-body">
            <h2 className="text-lg font-bold">Shopping Cart</h2>
            <div className="my-4">
              {Object.keys(cartItems).length===0&&(
                <div className='py-2 text-gray-500 text-center'>
                  You Don't have Any Items Yet!
                </div>
              )}
              {Object.values(cartItems).map((cartItem)=>(
                <div key={cartItem.user.id}>
                  {/*<small>{JSON.stringify(cartItem)}</small>*/}
                  <div className="flex items-center justify-between pb-4 border-b border-gray-300 mb-4">
                    <Link href='/public' className='underline'>
                      {cartItem.user.name}
                    </Link>
                    <div>
                      <form action={route('cart.checkout')} method='post'>
                        <input type="hidden" name='_token' value={csrf_token}/>
                        <input type="hidden" name='vendor_id' value={cartItem.user.id}/>
                        <button className='btn btn-sm btn-ghost'>
                          <CreditCardIcon className={'size-6'}/>
                          Pay Only For This Seller
                        </button>
                      </form>
                    </div>
                  </div>

                  {cartItem.items.map(item=>(
                    <CartItem item={item} key={item.id} />
                  ))}
                </div>
              ))}
            </div>
          </div>
        </div>
        <div className="card bg-white dark:bg-gray-800 lg:min-w-[260px] order1 lg:order-2">
          <div className="card-body">
            SubTotal ({totalQuantity} item): &nbsp;
            <CurrencyFormatter amount={totalPrice}/>
            <form action={route('cart.checkout')} method='post'>
              <input type="hidden" name='_token' value={csrf_token}/>
              <PrimaryButton className='rounded-full'>
                <CreditCardIcon className={"size-6"}/>
                Proceed To Checkout
              </PrimaryButton>
            </form>
          </div>
        </div>
      </div>
    </AuthenticatedLayout>
  );
}

export default Index;
